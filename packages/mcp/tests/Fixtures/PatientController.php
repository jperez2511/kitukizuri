<?php
namespace Icebearsoft\Kitukizuri\Mcp\Tests\Fixtures;
use Icebearsoft\Kitukizuri\Krud;
use Icebearsoft\Kitukizuri\Mcp\Concerns\ExposesMcp;
class PatientController extends Krud
{
    use ExposesMcp;
    public static int $constructions = 0;
    protected static function mcp(): array
    {
        return ['enabled' => true, 'operations' => ['list', 'get', 'create', 'update', 'delete'], 'company_column' => 'empresaid'];
    }
    public function __construct()
    {
        self::$constructions++;
        $this->setModel(new Patient);
        $this->setField(['field' => 'nombre', 'validation' => ['required', 'string', 'max:150']]);
        $this->setField(['field' => 'fecha_nacimiento', 'type' => 'date', 'validation' => ['required', 'date']]);
        foreach (['password', 'remember_token', 'private_note', 'empresaid'] as $field) {
            $this->setField(['field' => $field]);
            $this->setMcpField($field, ['read' => true, 'write' => true]);
        }
        $this->setMcpField('nombre', ['read' => true, 'write' => true]);
        $this->setMcpField('fecha_nacimiento', ['read' => true, 'write' => true]);
        $this->setWhere('active', 1);
        $this->setOrWhere('nombre', 'special');
    }
}
