ALTER TABLE `coupons`
    ADD COLUMN `start_date` DATE NULL DEFAULT NULL AFTER `unique_by_user`,
    ADD COLUMN `end_date` DATE NULL DEFAULT NULL AFTER `start_date`;
