<?php

namespace Icebearsoft\Kitukizuri\Mcp\Tests\Fixtures;

use Illuminate\Http\Request;

class CustomDeletePatientController extends PatientController
{
    public function destroy($id, Request $request)
    {
        abort(405, 'Records cannot be deleted.');
    }
}
