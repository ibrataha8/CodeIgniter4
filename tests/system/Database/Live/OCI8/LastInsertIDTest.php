<?php

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace CodeIgniter\Database\Live\OCI8;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * @group DatabaseLive
 *
 * @internal
 */
final class LastInsertIDTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $refresh = true;
    protected $seed    = 'Tests\Support\Database\Seeds\CITestSeeder';

    public function testGetInsertIDWithInsert()
    {
        $jobData = [
            'name'        => 'Grocery Sales',
            'description' => 'Discount!',
        ];

        $this->db->table('job')->insert($jobData);
        $actual = $this->db->insertID();

        $this->assertSame($actual, 5);
    }

    public function testGetInsertIDWithQuery()
    {
        $this->db->query('INSERT INTO "db_job" ("name", "description") VALUES (?, ?)', ['Grocery Sales', 'Discount!']);
        $actual = $this->db->insertID();

        $this->assertSame($actual, 5);
    }

    public function testGetInsertIDWithHasCommentQuery()
    {
        $sql = <<<SQL
-- INSERT INTO "db_misc" ("key", "value") VALUES ('key', 'value')
/* INSERT INTO "db_misc" ("key", "value") VALUES ('key', 'value') */
INSERT /* INTO "db_misc" */ INTO "db_job"  ("name", "description") VALUES (' INTO "abc"', ?)
SQL;
        $this->db->query($sql, ['Discount!']);
        $actual = $this->db->insertID();

        $this->assertSame($actual, 5);
    }
}
