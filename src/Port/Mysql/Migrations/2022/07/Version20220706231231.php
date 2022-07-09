<?php

declare(strict_types=1);

namespace App\Port\Mysql\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220706231231 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'CREATE TABLE alerts (
                    sensor_id VARCHAR(36) NOT NULL,
                    start_time DATETIME NOT NULL,
                    end_time DATETIME DEFAULT NULL,
                    measurements LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\',
                    INDEX sensor_id_idx (sensor_id),
                    INDEX end_time_idx (end_time)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB',
        );
        $this->addSql(
            'CREATE TABLE measurements (
                    sensor_id VARCHAR(36) NOT NULL,
                    co2 INT NOT NULL,
                    time DATETIME NOT NULL,
                    INDEX time_idx (time),
                    INDEX sensor_id_idx (sensor_id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB',
        );

        $this->addSql(
            'CREATE TABLE sensors (
                    id VARCHAR(36) NOT NULL,
                    status VARCHAR(10) NOT NULL,
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB',
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE alerts');
        $this->addSql('DROP TABLE measurements');
        $this->addSql('DROP TABLE sensors');
    }
}
