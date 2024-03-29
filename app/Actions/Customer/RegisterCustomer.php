<?php

namespace Tasawk\Actions\Customer;

use Exception;
use Lorisleiva\Actions\Concerns\AsAction;
use Notification;
use Tasawk\Models\Customer;

class RegisterCustomer
{
    use AsAction;


    /**
     * @throws Exception
     */
    public function handle($first_name, $last_name, $gender, $birth_date, $phone, $email, $device_token = null)
    {
        $customer = Customer::create([
            'name' => $first_name,
            'last_name' => $last_name,
            'gender' => $gender,
            'birth_date' => $birth_date,
            'active' => 1,
            'email' => $email,
            'phone' => $phone,
        ]);

        if ($device_token) {
            $customer->deviceTokens()->create(['token' => $device_token]);
        }
        //        TODO: uncomment this line to send notification to admins
        //        Notification::send(\AdminBase::administrationsUsers(), new NewCustomerRegisteredNotification($customer));
        return $customer;
    }
}
