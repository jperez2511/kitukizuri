<?php

namespace Icebearsoft\Kitukizuri\Mcp\Tests\Fixtures;

use Illuminate\Http\Request;

class CustomStorePatientController extends PatientController
{
    public function store(Request $request)
    {
        abort(405, 'Writes require the domain workflow.');
    }
}
