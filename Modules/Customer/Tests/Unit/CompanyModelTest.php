&lt;?php

namespace Modules\Customer\Tests\Unit;

use MicroweberPackages\Core\tests\TestCase;
use Modules\Customer\Models\Company;
use Modules\Customer\Models\Customer;

class CompanyModelTest extends TestCase
{
    public function testCompanyRelationships()
    {
        // Create company
        $company = Company::create([
            'name' => 'Test Company',
            'vat_number' => '123456789',
            'registration_number' => '987654321'
        ]);

        // Create customer associated with company
        $customer = Customer::create([
            'name' => 'Company Customer',
            'email' => 'company.customer@example.com',
            'company_id' => $company->id
        ]);

        // Test relationships
        $this->assertEquals(1, $company->customers->count());
        $this->assertEquals($customer->id, $company->customers->first()->id);
        $this->assertEquals($company->id, $customer->company->id);
        
        // Test company attributes
        $this->assertEquals('Test Company', $company->name);
        $this->assertEquals('123456789', $company->vat_number);
    }
}