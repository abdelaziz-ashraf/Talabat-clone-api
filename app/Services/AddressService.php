<?php

namespace App\Services;

use App\Models\Address;

class AddressService {
    public function update(Address $address, array $data) {
        $address->update($data);
        return $address;
    }

    public function destroy(Address $address) {
        $address->delete();
    }

    public function toggleActive(Address $address) {
        $address->update([
            'active' => (!$address->active)
        ]);
        return $address;
    }
}
