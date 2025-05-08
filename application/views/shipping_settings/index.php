<!DOCTYPE html>
<html lang="en">
<head>
    <title>ตั้งค่าการจัดส่งสินค้าและการเชื่อมต่อระบบ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>ตั้งค่าการจัดส่งสินค้าและการเชื่อมต่อระบบ</h1>

        <?php if ($this->session->flashdata('success')) : ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
        <?php endif; ?>

        <ul class="nav nav-tabs" id="settingsTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="shipping-tab" data-toggle="tab" href="#shipping" role="tab" aria-controls="shipping" aria-selected="true">การจัดส่งสินค้า</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="integration-tab" data-toggle="tab" href="#integration" role="tab" aria-controls="integration" aria-selected="false">การเชื่อมต่อระบบอื่นๆ</a>
            </li>
        </ul>

        <div class="tab-content mt-3">
            <div class="tab-pane fade show active" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                <h2>ตั้งค่าการจัดส่งสินค้า</h2>
                <form method="post" action="<?= site_url('shipping-settings/save_shipping_settings') ?>">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ชื่อวิธีการจัดส่ง</th>
                                <th>สถานะ</th>
                                <th>ลำดับ</th>
                                <th>การตั้งค่า</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($shipping_methods)) : ?>
                                <?php foreach ($shipping_methods as $method) : ?>
                                    <tr>
                                        <td><?= esc($method['display_name']) ?></td>
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="shipping_methods[<?= $method['method_id'] ?>][is_enabled]" value="1" <?= $method['is_enabled'] ? 'checked' : '' ?>>
                                                <label class="form-check-label">เปิดใช้งาน</label>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm" name="shipping_methods[<?= $method['method_id'] ?>][order]" value="<?= esc($method['order']) ?>">
                                        </td>
                                        <td>
                                            <?php if ($method['method_name'] == 'ems') : ?>
                                                <button type="button" class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#ems_settings">ตั้งค่า EMS</button>
                                            <?php elseif ($method['method_name'] == 'kerry') : ?>
                                                <button type="button" class="btn btn-sm btn-info" data-toggle="collapse" data-target="#kerry_settings">ตั้งค่า Kerry</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr><td colspan="4">ไม่พบวิธีการจัดส่ง</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <div class="collapse mt-3" id="ems_settings">
                        <h3>ตั้งค่า EMS</h3>
                        <div class="form-group">
                            <label>API Key:</label>
                            <input type="text" class="form-control" name="ems_settings[api_key]" value="<?= esc($ems_settings['api_key'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Account ID:</label>
                            <input type="text" class="form-control" name="ems_settings[account_id]" value="<?= esc($ems_settings['account_id'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>อัตราค่าจัดส่งพื้นฐาน:</label>
                            <input type="number" class="form-control" name="ems_settings[base_rate]" value="<?= esc($ems_settings['base_rate'] ?? '0.00') ?>" step="0.01">
                        </div>
                        <div class="form-group">
                            <label>อัตราค่าจัดส่งเพิ่มเติมต่อ KG:</label>
                            <input type="number" class="form-control" name="ems_settings[additional_rate_kg]" value="<?= esc($ems_settings['additional_rate_kg'] ?? '0.00') ?>" step="0.01">
                        </div>
                        <div class="form-group">
                            <label>ยอดสั่งซื้อขั้นต่ำสำหรับการจัดส่งฟรี:</label>
                            <input type="number" class="form-control" name="ems_settings[free_over_amount]" value="<?= esc($ems_settings['free_over_amount'] ?? '') ?>" step="0.01">
                        </div>
                    </div>

                    <div class="collapse mt-3" id="kerry_settings">
                        <h3>ตั้งค่า Kerry</h3>
                        <div class="form-group">
                            <label>API Key:</label>
                            <input type="text" class="form-control" name="kerry_settings[api_key]" value="<?= esc($kerry_settings['api_key'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>หมายเลขบัญชี:</label>
                            <input type="text" class="form-control" name="kerry_settings[account_number]" value="<?= esc($kerry_settings['account_number'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>ID ตารางอัตราค่าจัดส่ง:</label>
                            <input type="number" class="form-control" name="kerry_settings[rate_table_id]" value="<?= esc($kerry_settings['rate_table_id'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>อัตราค่าจัดส่งเริ่มต้น:</label>
                            <input type="number" class="form-control" name="kerry_settings[default_rate]" value="<?= esc($kerry_settings['default_rate'] ?? '0.00') ?>" step="0.01">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">บันทึกการตั้งค่าการจัดส่ง</button>
                </form>
            </div>

            <div class="tab-pane fade" id="integration" role="tabpanel" aria-labelledby="integration-tab">
                <h2>ตั้งค่าการเชื่อมต่อระบบอื่นๆ</h2>
                <form method="post" action="<?= site_url('shipping-settings/save_integration_settings') ?>">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ชื่อการเชื่อมต่อ</th>
                                <th>สถานะ</th>
                                <th>การตั้งค่า (JSON)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($integration_settings)) : ?>
                                <?php foreach ($integration_settings as $integration) : ?>
                                    <tr>
                                        <td><?= esc($integration['display_name']) ?></td>
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="integrations[<?= $integration['integration_id'] ?>][is_enabled]" value="1" <?= $integration['is_enabled'] ? 'checked' : '' ?>>
                                                <label class="form-check-label">เปิดใช้งาน</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea class="form-control" name="integrations[<?= $integration['integration_id'] ?>][config]"><?= esc($integration['config_json']) ?></textarea>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr><td colspan="3">ไม่พบการเชื่อมต่อระบบ</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary">บันทึกการตั้งค่าการเชื่อมต่อ</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
