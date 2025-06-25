-- Add posisi_id field to admin table
ALTER TABLE `admin` ADD `posisi_id` int(11) NULL AFTER `level`;

-- Add foreign key constraint
ALTER TABLE `admin` ADD CONSTRAINT `fk_admin_posisi` FOREIGN KEY (`posisi_id`) REFERENCES `posisi`(`posisi_id`) ON DELETE SET NULL ON UPDATE CASCADE; 