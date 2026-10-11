<?php

namespace MicroweberPackages\Install;

/**
 * The list of system schema definitions applied + seeded by the database
 * installer (\MicroweberPackages\DbInstaller\DbInstaller) on install /
 * self-heal.
 *
 * This list lives in the application (not in the db-installer package) so the
 * package stays schema-agnostic — the installer engine pulls the app-specific
 * schema providers from here.
 *
 * Each entry is a schema provider object; the installer uses those exposing
 * get() (array schema → build_table) and/or seed().
 */
class InstallSchemas
{
    /**
     * @return object[]
     */
    public static function get(): array
    {
        return [
            new Schema\Base(),
            //new Schema\Comments(),
            new Schema\Tags(),
            new Schema\JobsQueue(),
            new Schema\PasswordResetsTable(),
            new Schema\Updates(),
            new Schema\MailSubscribe(),
            new Schema\MailTemplates(),
        ];
    }
}
