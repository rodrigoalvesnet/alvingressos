ALTER TABLE `estadias`
    ADD COLUMN `desconto` DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER `valor_total`;
