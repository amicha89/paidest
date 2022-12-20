
-- CurrencyPaymentMethod

ALTER TABLE `currency_payment_methods` CHANGE `activated_for` `activated_for` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'deposit, withdrawal single, both or none';