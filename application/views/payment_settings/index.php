

<!DOCTYPE html>
<html lang="en">
<head>
    <title>ตั้งค่าระบบชำระเงิน</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>ตั้งค่าระบบชำระเงิน</h1>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
        <?php endif; ?>

        <ul class="nav nav-tabs" id="paymentTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">ทั่วไป</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="paypal-tab" data-toggle="tab" href="#paypal" role="tab" aria-controls="paypal" aria-selected="false">PayPal</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="stripe-tab" data-toggle="tab" href="#stripe" role="tab" aria-controls="stripe" aria-selected="false">Stripe</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="banktransfer-tab" data-toggle="tab" href="#banktransfer" role="tab" aria-controls="banktransfer" aria-selected="false">โอนเงินผ่านธนาคาร</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="promptpay-tab" data-toggle="tab" href="#promptpay" role="tab" aria-controls="promptpay" aria-selected="false">PromptPay</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="ewallets-tab" data-toggle="tab" href="#ewallets" role="tab" aria-controls="ewallets" aria-selected="false">E-wallets</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="paymentgateways-tab" data-toggle="tab" href="#paymentgateways" role="tab" aria-controls="paymentgateways" aria-selected="false">Payment Gateways</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-tab" data-toggle="tab" href="#custom" role="tab" aria-controls="custom" aria-selected="false">กำหนดเอง</a>
            </li>
        </ul>

        <div class="tab-content mt-3">
            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                <h3>การตั้งค่าทั่วไป</h3>
                <p>ส่วนนี้อาจมีตัวเลือกเพิ่มเติมเกี่ยวกับการทำงานของระบบชำระเงินโดยรวม</p>
                <button type="submit" class="btn btn-primary">บันทึกการตั้งค่าทั่วไป</button>
            </div>

            <div class="tab-pane fade" id="paypal" role="tabpanel" aria-labelledby="paypal-tab">
                <?= form_open('payment-settings/save_settings'); ?>
                    <h3>การตั้งค่า PayPal</h3>
                    <div class="form-group">
                        <label for="paypal_status">สถานะ:</label>
                        <select class="form-control" id="paypal_status" name="paypal_status">
                            <option value="1" <?= ($paypal_settings['is_enabled'] ?? 0) == 1 ? 'selected' : '' ?>>เปิดใช้งาน</option>
                            <option value="0" <?= ($paypal_settings['is_enabled'] ?? 0) == 0 ? 'selected' : '' ?>>ปิดใช้งาน</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="paypal_client_id">Client ID:</label>
                        <input type="text" class="form-control" id="paypal_client_id" name="paypal_client_id" value="<?= esc($paypal_settings['client_id'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="paypal_secret">Secret:</label>
                        <input type="text" class="form-control" id="paypal_secret" name="paypal_secret" value="<?= esc($paypal_settings['secret'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="paypal_mode">Mode:</label>
                        <select class="form-control" id="paypal_mode" name="paypal_mode">
                            <option value="sandbox" <?= ($paypal_settings['mode'] ?? '') == 'sandbox' ? 'selected' : '' ?>>Sandbox</option>
                            <option value="live" <?= ($paypal_settings['mode'] ?? '') == 'live' ? 'selected' : '' ?>>Live</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="paypal_currency">Currency:</label>
                        <input type="text" class="form-control" id="paypal_currency" name="paypal_currency" value="<?= esc($paypal_settings['currency'] ?? 'USD') ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">บันทึกการตั้งค่า PayPal</button>
                <?= form_close(); ?>
            </div>

            <div class="tab-pane fade" id="stripe" role="tabpanel" aria-labelledby="stripe-tab">
                <?= form_open('payment-settings/save_settings'); ?>
                    <h3>การตั้งค่า Stripe</h3>
                    <div class="form-group">
                        <label for="stripe_status">สถานะ:</label>
                        <select class="form-control" id="stripe_status" name="stripe_status">
                            <option value="1" <?= ($stripe_settings['is_enabled'] ?? 0) == 1 ? 'selected' : '' ?>>เปิดใช้งาน</option>
                            <option value="0" <?= ($stripe_settings['is_enabled'] ?? 0) == 0 ? 'selected' : '' ?>>ปิดใช้งาน</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="stripe_publishable_key">Publishable Key:</label>
                        <input type="text" class="form-control" id="stripe_publishable_key" name="stripe_publishable_key" value="<?= esc($stripe_settings['publishable_key'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="stripe_secret_key">Secret Key:</label>
                        <input type="text" class="form-control" id="stripe_secret_key" name="stripe_secret_key" value="<?= esc($stripe_settings['secret_key'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="stripe_webhook_secret">Webhook Secret:</label>
                        <input type="text" class="form-control" id="stripe_webhook_secret" name="stripe_webhook_secret" value="<?= esc($stripe_settings['webhook_secret'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="stripe_mode">Mode:</label>
                        <select class="form-control" id="stripe_mode" name="stripe_mode">
                            <option value="test" <?= ($stripe_settings['mode'] ?? '') == 'test' ? 'selected' : '' ?>>Test</option>
                            <option value="live" <?= ($stripe_settings['mode'] ?? '') == 'live' ? 'selected' : '' ?>>Live</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="stripe_currency">Currency:</label>
                        <input type="text" class="form-control" id="stripe_currency" name="stripe_currency" value="<?= esc($stripe_settings['currency'] ?? 'USD') ?>">
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="stripe_enable_card" name="stripe_enable_card" value="1" <?= ($stripe_settings['enable_card'] ?? 1) == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label" for="stripe_enable_card">เปิดใช้งานการชำระด้วยบัตรเครดิต/เดบิต</label>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="stripe_enable_apple_pay" name="stripe_enable_apple_pay" value="1" <?= ($stripe_settings['enable_apple_pay'] ?? 0) == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label" for="stripe_enable_apple_pay">เปิดใช้งาน Apple Pay</label>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="stripe_enable_google_pay" name="stripe_enable_google_pay" value="1" <?= ($stripe_settings['enable_google_pay'] ?? 0) == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label" for="stripe_enable_google_pay">เปิดใช้งาน Google Pay</label>
                    </div>
                    <button type="submit" class="btn btn-primary">บันทึกการตั้งค่า Stripe</button>
                <?= form_close(); ?>
            </div>

            <div class="tab-pane fade" id="banktransfer" role="tabpanel" aria-labelledby="banktransfer-tab">
                <?= form_open('payment-settings/save_settings'); ?>
                    <h3>การตั้งค่าโอนเงินผ่านธนาคาร</h3>
                    <div class="form-group">
                        <label for="bank_transfer_status">สถานะ:</label>
                        <select class="form-control" id="bank_transfer_status" name="bank_transfer_status">
                            <option value="1" <?= ($bank_transfer_settings['is_enabled'] ?? 0) == 1 ? 'selected' : '' ?>>เปิดใช้งาน</option>
                            <option value="0" <?= ($bank_transfer_settings['is_enabled'] ?? 0) == 0 ? 'selected' : '' ?>>ปิดใช้งาน</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="bank_transfer_instructions">คำแนะนำ:</label>
                        <textarea class="form-control" id="bank_transfer_instructions" name="bank_transfer_instructions"><?= esc($bank_transfer_settings['instructions'] ?? '') ?></textarea>
                        <small class="form-text text-muted">คำแนะนำที่ผู้ใช้จะเห็นเมื่อเลือกช่องทางการชำระเงินนี้</small>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="bank_transfer_notification" name="bank_transfer_notification" value="1" <?= ($bank_transfer_settings['enable_notification'] ?? 0) == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label" for="bank_transfer_notification">เปิดใช้งานการแจ้งเตือนการชำระเงิน</label>
                    </div>
                    <div class="form-group">
                        <label for="bank_transfer_notification_description">คำอธิบายการแจ้งเตือน:</label>
                        <textarea class="form-control" id="bank_transfer_notification_description" name="bank_transfer_notification_description"><?= esc($bank_transfer_settings['notification_description'] ?? '') ?></textarea>
                        <small class="form-text text-muted">คำอธิบายเพิ่มเติมเกี่ยวกับการแจ้งเตือนการชำระเงิน</small>
                    </div>

                    <h4>บัญชีธนาคาร</h4>
                    <div id="bank_accounts_list">
                        <?php if (!empty($bank_accounts)): ?>
                            <?php foreach ($bank_accounts as $key => $account): ?>
                                <div class="bank-account row mb-3">
                                    <input type="hidden" name="account_id[]" value="<?= esc($account['account_id']) ?>">
                                    <div class="col-md-3">
                                        <label for="bank_name_<?= $key ?>" class="form-label">ธนาคาร:</label>
                                        <select class="form-select" id="bank_name_<?= $key ?>" name="bank_name[]">
                                            <option value="scb" <?= ($account['bank_name'] ?? '') == 'scb' ? 'selected' : '' ?>>ไทยพาณิชย์</option>
                                            <option value="kbank" <?= ($account['bank_name'] ?? '') == 'kbank' ? 'selected' : '' ?>>กสิกรไทย</option>
                                            <option value="bbl" <?= ($account['bank_name'] ?? '') == 'bbl' ? 'selected' : '' ?>>กรุงเทพ</option>
                                            <option value="ktb" <?= ($account['bank_name'] ?? '') == 'ktb' ? 'selected' : '' ?>>กรุงไทย</option>
                                            <option value="ttb" <?= ($account['bank_name'] ?? '') == 'ttb' ? 'selected' : '' ?>>ทหารไทยธนชาต</option>
                                            <option value="bay" <?= ($account['bank_name'] ?? '') == 'bay' ? 'selected' : '' ?>>กรุงศรีอยุธยา</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="account_name_<?= $key ?>" class="form-label">ชื่อบัญชี:</label>
                                        <input type="text" class="form-control" id="account_name_<?= $key ?>" name="account_name[]" value="<?= esc($account['account_name'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="account_number_<?= $key ?>" class="form-label">หมายเลขบัญชี:</label>
                                        <input type="text" class="form-control" id="account_number_<?= $key ?>" name="account_number[]" value="<?= esc($account['account_number'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" id="is_active_<?= $key ?>" name="is_active[]" value="<?= esc($account['account_id']) ?>" <?= $account['is_active'] ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="is_active_<?= $key ?>">ใช้งาน</label>
                                        </div>
                                    </div>
                                    <div class="col-md-1 mt-4">
                                        <button type="button" class="btn btn-danger btn-sm remove-bank-account" data-account-id="<?= esc($account['account_id']) ?>">ลบ</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p id="no_accounts">ยังไม่มีบัญชีธนาคารที่เพิ่ม</p>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-success btn-sm mt-2" id="add_bank_account">เพิ่มบัญชีธนาคาร</button>

                    <button type="submit" class="btn btn-primary mt-3">บันทึกการตั้งค่าโอนเงินผ่านธนาคาร</button>
                <?= form_close(); ?>
            </div>

            <div class="tab-pane fade" id="promptpay" role="tabpanel" aria-labelledby="promptpay-tab">
                <?= form_open('payment-settings/save_settings'); ?>
                    <h3>การตั้งค่า PromptPay</h3>
                    <div class="form-group">
                        <label for="promptpay_status">สถานะ:</label>
                        <select class="form-control" id="promptpay_status" name="promptpay_status">
                            <option value="1" <?= ($promptpay_settings['is_enabled'] ?? 0) == 1 ? 'selected' : '' ?>>เปิดใช้งาน</option>
                            <option value="0" <?= ($promptpay_settings['is_enabled'] ?? 0) == 0 ? 'selected' : '' ?>>ปิดใช้งาน</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="promptpay_type">ประเภท:</label>
                        <select class="form-control" id="promptpay_type" name="promptpay_type">
                            <option value="mobile" <?= ($promptpay_settings['type'] ?? '') == 'mobile' ? 'selected' : '' ?>>เบอร์โทรศัพท์</option>
                            <option value="id" <?= ($promptpay_settings['type'] ?? '') == 'id' ? 'selected' : '' ?>>เลขประจำตัวผู้เสียภาษี/บัตรประชาชน</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="promptpay_merchant_id">หมายเลข PromptPay:</label>
                        <input type="text" class="form-control" id="promptpay_merchant_id" name="promptpay_merchant_id" value="<?= esc($promptpay_settings['merchant_id'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="promptpay_store_name">ชื่อร้านค้า (ถ้ามี):</label>
                        <input type="text" class="form-control" id="promptpay_store_name" name="promptpay_store_name" value="<?= esc($promptpay_settings['store_name'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="promptpay_mobile_number">เบอร์โทรศัพท์ (ถ้าเลือก):</label>
                        <input type="text" class="form-control" id="promptpay_mobile_number" name="promptpay_mobile_number" value="<?= esc($promptpay_settings['mobile_number'] ?? '') ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">บันทึกการตั้งค่า PromptPay</button>
                <?= form_close(); ?>
            </div>

            <div class="tab-pane fade" id="ewallets" role="tabpanel" aria-labelledby="ewallets-tab">
                <?= form_open('payment-settings/save_settings'); ?>
                    <h3>การตั้งค่า E-wallets</h3>
                    <div class="form-group">
                        <label for="ewallets_status">สถานะ:</label>
                        <select class="form-control" id="ewallets_status" name="ewallets_status">
                            <option value="1" <?= ($ewallet_settings['is_enabled'] ?? 0) == 1 ? 'selected' : '' ?>>เปิดใช้งาน</option>
                            <option value="0" <?= ($ewallet_settings['is_enabled'] ?? 0) == 0 ? 'selected' : '' ?>>ปิดใช้งาน</option>
                        </select>
                    </div>
                    <h4>TrueMoney Wallet</h4>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="truemoney_enabled" name="truemoney_enabled" value="1" <?= ($ewallet_settings['enable_truemoney'] ?? 0) == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label" for="truemoney_enabled">เปิดใช้งาน TrueMoney Wallet</label>
                    </div>
                    <div class="form-group">
                        <label for="truemoney_config">การตั้งค่า TrueMoney (JSON):</label>
                        <textarea class="form-control" id="truemoney_config" name="truemoney_config"><?= esc($ewallet_settings['truemoney_config'] ?? '') ?></textarea>
                    </div>
                    <h4>Rabbit LINE Pay</h4>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="rabbitlinepay_enabled" name="rabbitlinepay_enabled" value="1" <?= ($ewallet_settings['enable_rabbitlinepay'] ?? 0) == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label" for="rabbitlinepay_enabled">เปิดใช้งาน Rabbit LINE Pay</label>
                    </div>
                    <div class="form-group">
                        <label for="rabbitlinepay_config">การตั้งค่า Rabbit LINE Pay (JSON):</label>
                        <textarea class="form-control" id="rabbitlinepay_config" name="rabbitlinepay_config"><?= esc($ewallet_settings['rabbitlinepay_config'] ?? '') ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">บันทึกการตั้งค่า E-wallets</button>
                <?= form_close(); ?>
            </div>

            <div class="tab-pane fade" id="paymentgateways" role="tabpanel" aria-labelledby="paymentgateways-tab">
                <?= form_open('payment-settings/save_settings'); ?>
                    <h3>การตั้งค่า Payment Gateways</h3>
                    <div class="form-group">
                        <label for="payment_gateways_status">สถานะ:</label>
                        <select class="form-control" id="payment_gateways_status" name="payment_gateways_status">
                            <option value="1" <?= ($payment_gateway_settings['is_enabled'] ?? 0) == 1 ? 'selected' : '' ?>>เปิดใช้งาน</option>
                            <option value="0" <?= ($payment_gateway_settings['is_enabled'] ?? 0) == 0 ? 'selected' : '' ?>>ปิดใช้งาน</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="payment_gateway_provider">ผู้ให้บริการ:</label>
                        <select class="form-control" id="payment_gateway_provider" name="payment_gateway_provider">
                            <option value="">-- เลือกผู้ให้บริการ --</option>
                            <?php if (!empty($payment_gateway_providers)): ?>
                                <?php foreach ($payment_gateway_providers as $provider): ?>
                                    <option value="<?= esc($provider['provider_name']) ?>" <?= ($payment_gateway_settings['provider_id'] ?? '') == $provider['provider_id'] ? 'selected' : '' ?>><?= esc($provider['provider_name']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="payment_gateway_config">Config (JSON):</label>
                        <textarea class="form-control" id="payment_gateway_config" name="payment_gateway_config"><?= esc($payment_gateway_settings['config_json'] ?? '') ?></textarea>
                        <small class="form-text text-muted">การตั้งค่าเพิ่มเติมสำหรับ Payment Gateway (เช่น API Keys)</small>
                    </div>
                    <div class="form-group">
                        <label for="payment_gateway_mode">Mode:</label>
                        <select class="form-control" id="payment_gateway_mode" name="payment_gateway_mode">
                            <option value="sandbox" <?= ($payment_gateway_settings['mode'] ?? '') == 'sandbox' ? 'selected' : '' ?>>Sandbox</option>
                            <option value="live" <?= ($payment_gateway_settings['mode'] ?? '') == 'live' ? 'selected' : '' ?>>Live</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="payment_gateway_currency">Currency:</label>
                        <input type="text" class="form-control" id="payment_gateway_currency" name="payment_gateway_currency" value="<?= esc($payment_gateway_settings['currency'] ?? 'THB') ?>">
                    </div>
                    <div class="form-group">
                        <label for="payment_gateway_channels">ช่องทางที่เปิดใช้งาน (comma-separated):</label>
                        <input type="text" class="form-control" id="payment_gateway_channels" name="payment_gateway_channels" value="<?= esc($payment_gateway_settings['enabled_channels'] ?? '') ?>">
                        <small class="form-text text-muted">ระบุช่องทางที่ต้องการเปิดใช้งาน (เช่น credit_card, promptpay)</small>
                    </div>
                    <button type="submit" class="btn btn-primary">บันทึกการตั้งค่า Payment Gateways</button>
                <?= form_close(); ?>
            </div>

            <div class="tab-pane fade" id="custom" role="tabpanel" aria-labelledby="custom-tab">
                <?= form_open('payment-settings/save_settings'); ?>
                    <h3>การตั้งค่ากำหนดเอง</h3>
                    <div class="form-group">
                        <label for="custom_status">สถานะ:</label>
                        <select class="form-control" id="custom_status" name="custom_status">
                            <option value="1" <?= ($custom_payment_settings['is_enabled'] ?? 0) == 1 ? 'selected' : '' ?>>เปิดใช้งาน</option>
                            <option value="0" <?= ($custom_payment_settings['is_enabled'] ?? 0) == 0 ? 'selected' : '' ?>>ปิดใช้งาน</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="custom_payment_name">ชื่อช่องทางการชำระเงิน:</label>
                        <input type="text" class="form-control" id="custom_payment_name" name="custom_payment_name" value="<?= esc($custom_payment_settings['payment_name'] ?? '') ?>">
                        <small class="form-text text-muted">ชื่อที่จะแสดงให้ผู้ใช้เห็น</small>
                    </div>
                    <div class="form-group">
                        <label for="custom_description">คำอธิบาย:</label>
                        <textarea class="form-control" id="custom_description" name="custom_description"><?= esc($custom_payment_settings['description'] ?? '') ?></textarea>
                        <small class="form-text text-muted">คำอธิบายเพิ่มเติมเกี่ยวกับช่องทางการชำระเงินนี้</small>
                    </div>
                    <div class="form-group">
                        <label for="custom_config">Config (JSON):</label>
                        <textarea class="form-control" id="custom_config" name="custom_config"><?= esc($custom_payment_settings['config_json'] ?? '') ?></textarea>
                        <small class="form-text text-muted">การตั้งค่าเพิ่มเติม (ถ้ามี) ในรูปแบบ JSON</small>
                    </div>
                    <button type="submit" class="btn btn-primary">บันทึกการตั้งค่ากำหนดเอง</button>
                <?= form_close(); ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#paymentTab a').on('click', function (e) {
                e.preventDefault()
                $(this).tab('show')
            });

            // เพิ่มบัญชีธนาคารแบบ Dynamic
            $('#add_bank_account').click(function() {
                var index = $('#bank_accounts_list .bank-account').length;
                var newAccount = `
                    <div class="bank-account row mb-3">
                        <input type="hidden" name="account_id[]" value="new">
                        <div class="col-md-3">
                            <label for="bank_name_${index}" class="form-label">ธนาคาร:</label>
                            <select class="form-select" id="bank_name_${index}" name="bank_name[]">
                                <option value="scb">ไทยพาณิชย์</option>
                                <option value="kbank">กสิกรไทย</option>
                                <option value="bbl">กรุงเทพ</option>
                                <option value="ktb">กรุงไทย</option>
                                <option value="ttb">ทหารไทยธนชาต</option>
                                <option value="bay">กรุงศรีอยุธยา</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="account_name_${index}" class="form-label">ชื่อบัญชี:</label>
                            <input type="text" class="form-control" id="account_name_${index}" name="account_name[]">
                        </div>
                        <div class="col-md-3">
                            <label for="account_number_${index}" class="form-label">หมายเลขบัญชี:</label>
                            <input type="text" class="form-control" id="account_number_${index}" name="account_number[]">
                        </div>
                        <div class="col-md-2">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="is_active_${index}" name="is_active[]" value="-1" checked>
                                <label class="form-check-label" for="is_active_${index}">ใช้งาน</label>
                            </div>
                        </div>
                        <div class="col-md-1 mt-4">
                            <button type="button" class="btn btn-danger btn-sm remove-bank-account">ลบ</button>
                        </div>
                    </div>
                `;
                $('#bank_accounts_list').append(newAccount);
                $('#no_accounts').remove();
            });

            // ลบบัญชีธนาคารแบบ Dynamic
            $(document).on('click', '.remove-bank-account', function() {
                if ($('#bank_accounts_list .bank-account').length > 1 || $('#no_accounts').length > 0) {
                    $(this).closest('.bank-account').remove();
                    if ($('#bank_accounts_list .bank-account').length === 0) {
                        $('#bank_accounts_list').html('<p id="no_accounts">ยังไม่มีบัญชีธนาคารที่เพิ่ม</p>');
                    }
                } else {
                    alert('ต้องมีบัญชีธนาคารอย่างน้อย 1 บัญชี');
                }
            });
        });
    </script>
</body>
</html>
