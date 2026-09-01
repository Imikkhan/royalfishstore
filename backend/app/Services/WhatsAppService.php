<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    // Meta Cloud API Config
    protected string $metaApiUrl;
    protected string $phoneNumberId;
    protected string $metaAccessToken;
    protected string $templateName;
    protected string $templateLang;

    // Codebey API Config
    protected string $codebeyApiUrl;
    protected string $codebeyClientId;
    protected string $codebeyClientSecret;
    protected string $codebeyTemplateId;
    protected string $codebeyTemplateName;

    public function __construct()
    {
        $this->metaApiUrl = config('services.whatsapp.api_url', 'https://graph.facebook.com/v22.0');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id', '1261359957063709');
        $this->metaAccessToken = config('services.whatsapp.access_token', '');
        $this->templateName = config('services.whatsapp.template_name', 'otpsms_verification');
        $this->templateLang = config('services.whatsapp.template_lang', 'en_US');

        $this->codebeyApiUrl = config('services.codebey.api_url', 'https://codebey.online/external-api');
        $this->codebeyClientId = config('services.codebey.client_id', 'ci_1JPP85AUFD1NVM7NNDSQ');
        $this->codebeyClientSecret = config('services.codebey.client_secret', 'cs_6FPDTOC7VYMDNGMR2T5Q');
        $this->codebeyTemplateId = (string) config('services.codebey.template_id', '2846735265701532');
        $this->codebeyTemplateName = (string) config('services.codebey.template_name', 'otpsms_verification');
    }

    /**
     * Format phone number into numeric components.
     */
    public function parsePhone(string $phone): array
    {
        $clean = preg_replace('/\D/', '', $phone);
        if (strlen($clean) === 10) {
            return ['code' => '91', 'number' => $clean, 'full' => '91' . $clean];
        }
        if (strlen($clean) === 12 && str_starts_with($clean, '91')) {
            return ['code' => '91', 'number' => substr($clean, 2), 'full' => $clean];
        }
        return ['code' => '91', 'number' => $clean, 'full' => $clean];
    }

    /**
     * Send OTP Verification message via Codebey or Meta WhatsApp API.
     */
    public function sendOtp(string $phone, string $otp): array
    {
        $parsed = $this->parsePhone($phone);
        $mobileCode = $parsed['code'];
        $mobileNumber = $parsed['number'];
        $fullPhone = $parsed['full'];

        $lastError = null;
        $ipToWhitelist = null;

        // ----------------------------------------------------
        // 1. PRIMARY METHOD: Meta WhatsApp Cloud API (Official Template)
        // ----------------------------------------------------
        if (!empty($this->metaAccessToken)) {
            $metaUrl = rtrim($this->metaApiUrl, '/') . '/' . $this->phoneNumberId . '/messages';

            $templatePayloads = [
                // 1.A: otpsms_verification template with Body & URL Button parameters
                [
                    'messaging_product' => 'whatsapp',
                    'recipient_type' => 'individual',
                    'to' => $fullPhone,
                    'type' => 'template',
                    'template' => [
                        'name' => $this->templateName,
                        'language' => ['code' => $this->templateLang],
                        'components' => [
                            [
                                'type' => 'body',
                                'parameters' => [
                                    ['type' => 'text', 'text' => $otp]
                                ]
                            ],
                            [
                                'type' => 'button',
                                'sub_type' => 'url',
                                'index' => '0',
                                'parameters' => [
                                    ['type' => 'text', 'text' => $otp]
                                ]
                            ]
                        ]
                    ]
                ],
                // 1.B: otpsms_verification with copy_code button
                [
                    'messaging_product' => 'whatsapp',
                    'recipient_type' => 'individual',
                    'to' => $fullPhone,
                    'type' => 'template',
                    'template' => [
                        'name' => $this->templateName,
                        'language' => ['code' => $this->templateLang],
                        'components' => [
                            [
                                'type' => 'body',
                                'parameters' => [
                                    ['type' => 'text', 'text' => $otp]
                                ]
                            ],
                            [
                                'type' => 'button',
                                'sub_type' => 'copy_code',
                                'index' => '0',
                                'parameters' => [
                                    ['type' => 'coupon_code', 'coupon_code' => $otp]
                                ]
                            ]
                        ]
                    ]
                ],
                // 1.C: otpsms_verification body-only template
                [
                    'messaging_product' => 'whatsapp',
                    'recipient_type' => 'individual',
                    'to' => $fullPhone,
                    'type' => 'template',
                    'template' => [
                        'name' => $this->templateName,
                        'language' => ['code' => $this->templateLang],
                        'components' => [
                            [
                                'type' => 'body',
                                'parameters' => [
                                    ['type' => 'text', 'text' => $otp]
                                ]
                            ]
                        ]
                    ]
                ],
            ];

            foreach ($templatePayloads as $payload) {
                try {
                    $response = Http::withToken($this->metaAccessToken)
                        ->timeout(6)
                        ->post($metaUrl, $payload);

                    $resJson = $response->json();
                    Log::info("Meta WhatsApp API Response to {$fullPhone}: Status {$response->status()}", [
                        'body' => $resJson
                    ]);

                    if ($response->successful()) {
                        return [
                            'success' => true,
                            'provider' => 'meta_graph',
                            'message' => 'WhatsApp OTP sent successfully via Meta Cloud API.',
                            'data' => $resJson
                        ];
                    } else {
                        if (isset($resJson['error']['message'])) {
                            $lastError = 'Meta API: ' . $resJson['error']['message'];
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning("Meta Graph API Exception: " . $e->getMessage());
                    $lastError = 'Meta API Exception: ' . $e->getMessage();
                }
            }
        }

        // ----------------------------------------------------
        // 2. FALLBACK METHOD: Codebey WhatsApp API
        // ----------------------------------------------------
        if (!empty($this->codebeyClientId) && !empty($this->codebeyClientSecret)) {
            $codebeyHeaders = [
                'client-id' => $this->codebeyClientId,
                'client-secret' => $this->codebeyClientSecret,
            ];

            // 2.A Try Codebey Send Template Message
            $templateIdentifiers = array_unique(array_filter([
                $this->codebeyTemplateId,
                $this->codebeyTemplateName,
            ]));

            $templateUrl = rtrim($this->codebeyApiUrl, '/') . '/inbox/send-template-message';

            foreach ($templateIdentifiers as $tplId) {
                try {
                    $response = Http::withHeaders($codebeyHeaders)
                        ->timeout(8)
                        ->asForm()
                        ->post($templateUrl, [
                            'mobile_code' => $mobileCode,
                            'mobile' => $mobileNumber,
                            'template_id' => $tplId,
                            'custom_fields' => json_encode([$otp]),
                        ]);

                    $body = $response->json();
                    Log::info("Codebey Template ({$tplId}) Response to {$fullPhone}: Status {$response->status()}", ['body' => $body]);

                    if ($response->successful() && ($body['status'] ?? '') === 'success') {
                        return [
                            'success' => true,
                            'provider' => 'codebey_template',
                            'message' => 'WhatsApp OTP sent successfully via Codebey Template.',
                            'data' => $body
                        ];
                    }

                    if (isset($body['message']) && is_array($body['message'])) {
                        $joinedMsg = implode(' ', $body['message']);
                        if (preg_match('/add your IP address \(([0-9\.]+)\)/i', $joinedMsg, $m)) {
                            $ipToWhitelist = $m[1];
                        }
                        $lastError = $joinedMsg;
                    }
                } catch (\Exception $e) {
                    Log::warning("Codebey Template ({$tplId}) Exception: " . $e->getMessage());
                    $lastError = $e->getMessage();
                }
            }

            // 2.B Try Codebey Direct WhatsApp Message
            try {
                $messageUrl = rtrim($this->codebeyApiUrl, '/') . '/inbox/send-message';
                $response = Http::withHeaders($codebeyHeaders)
                    ->timeout(6)
                    ->asForm()
                    ->post($messageUrl, [
                        'mobile_code' => $mobileCode,
                        'mobile' => $mobileNumber,
                        'message' => "🔐 Your Royal Fish Store login OTP is *{$otp}*. Valid for 5 minutes. Please do not share this OTP."
                    ]);

                $body = $response->json();
                Log::info("Codebey Direct Message Response to {$fullPhone}: Status {$response->status()}", ['body' => $body]);

                if ($response->successful() && ($body['status'] ?? '') === 'success') {
                    return [
                        'success' => true,
                        'provider' => 'codebey_message',
                        'message' => 'WhatsApp OTP sent successfully via Codebey.',
                        'data' => $body
                    ];
                }

                if (isset($body['message']) && is_array($body['message'])) {
                    $joinedMsg = implode(' ', $body['message']);
                    if (preg_match('/add your IP address \(([0-9\.]+)\)/i', $joinedMsg, $m)) {
                        $ipToWhitelist = $m[1];
                    }
                    $lastError = $joinedMsg;
                }
            } catch (\Exception $e) {
                Log::warning("Codebey Direct Message Exception: " . $e->getMessage());
                $lastError = $e->getMessage();
            }
        }

        $detectedIp = $ipToWhitelist ?: request()->ip();

        return [
            'success' => false,
            'message' => $ipToWhitelist 
                ? "Codebey IP Restriction: Please whitelist IP {$ipToWhitelist} in your Codebey dashboard."
                : "WhatsApp gateway returned an error: {$lastError}",
            'ip_to_whitelist' => $ipToWhitelist ?: $detectedIp,
            'last_error' => $lastError
        ];
    }
}
