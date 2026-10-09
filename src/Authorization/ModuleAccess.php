<?php

namespace Icebearsoft\Kitukizuri\Authorization;

use Icebearsoft\Kitukizuri\App\Models\Modulo;
use Icebearsoft\Kitukizuri\App\Models\ModuloEmpresas;
use Icebearsoft\Kitukizuri\App\Models\UsuarioRol;

/** Existing Kitukizuri authorization, independent of HTTP routes and guards. */
class ModuleAccess
{
    public function companyAllows($user, string $module): bool
    {
        if (!$user) {
            return false;
        }

        if (empty($user->empresaid)) {
            return true;
        }

        return ModuloEmpresas::where('empresaid', $user->empresaid)
            ->where('moduloid', Modulo::where('ruta', $module)->value('moduloid'))
            ->exists();
    }

    public function permits($user, string $module, array $actions): bool
    {
        $id = Modulo::where('ruta', $module)->value('moduloid');

        return $user && $id && $actions
            ? UsuarioRol::getPermisosAsignados($user->getAuthIdentifier(), $id, $actions)
            : false;
    }

    /** All effective module actions, with the same company rule as validar(). */
    public function grants($user): array
    {
        if (!$user) {
            return [];
        }

        $query = UsuarioRol::permissionQuery($user->getAuthIdentifier())
            ->join('modulos as m', 'm.moduloid', '=', 'mP.moduloid');

        if (!empty($user->empresaid)) {
            $query->whereIn('mP.moduloid', ModuloEmpresas::where('empresaid', $user->empresaid)->select('moduloid'));
        }

        $grants = [];
        foreach ($query->select('m.ruta', 'p.nombreLaravel')->distinct()->get() as $row) {
            $grants[$row->ruta][] = $row->nombreLaravel;
        }

        return $grants;
    }
}
