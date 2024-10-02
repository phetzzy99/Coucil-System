<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Get all permission from database
     *
     * @return \Illuminate\Contracts\View\View
     *
     * This method will retrieve all permissions from the database and
     * pass it to the view.
     */
    public function AllPermission()
    {
        // Retrieve all permission from the database
        $permissions = Permission::all();

        // Pass the permissions to the view
        return view('admin.backend.pages.permission.all_permission', compact('permissions'));
    }

    public function AddPermission()
    {
        return view('admin.backend.pages.permission.add_permission');
    }


    public function StorePermission(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'group_name' => 'required',
        ]);
        Permission::create([
            'name' => $request->name,
            'group_name' => $request->group_name
        ]);
        $notification = array(
            'message' => 'Permission Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.permission')->with($notification);
    }

    public function EditPermission($id)
    {
        $permission = Permission::find($id);
        return view('admin.backend.pages.permission.edit_permission', compact('permission'));
    }

    public function UpdatePermission(Request $request)
    {
        $per_id = $request->id;
        Permission::find($per_id)->update([
            'name' => $request->name,
            'group_name' => $request->group_name
        ]);
        $notification = array(
            'message' => 'Permission Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.permission')->with($notification);
    }

    public function DeletePermission($id)
    {
        Permission::find($id)->delete();
        $notification = array(
            'message' => 'Permission Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    // ALL ROLE METHOD
    public function AllRole()
    {
        $roles = Role::all();
        return view('admin.backend.pages.roles.all_roles', compact('roles'));
    }


    public function AddRole()
    {
        return view('admin.backend.pages.roles.add_roles');
    }


    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $role_name = Role::where('name', $request->name)->first();

        if ($role_name) {
            $notification = [
                'message' => 'Role Already Exists!',
                'alert-type' => 'error'
            ];
        } else {
            try {
                Role::create(['name' => $request->name]);
                $notification = [
                    'message' => 'Role Added Successfully',
                    'alert-type' => 'success'
                ];
            } catch (\Exception $e) {
                $notification = [
                    'message' => 'Something went wrong!',
                    'alert-type' => 'error'
                ];
            }
        }

        return redirect()->route('all.roles')->with($notification);
    }

    public function EditRole($id)
    {
        $roles = Role::find($id);
        return view('admin.backend.pages.roles.edit_roles', compact('roles'));
    }

    public function UpdateRole(Request $request)
    {
        $role_id = $request->id;
        $role = Role::find($role_id);

        if ($role->name != $request->name) {
            $role_name = Role::where('name', $request->name)->first();
            if ($role_name) {
                $notification = array(
                    'message' => 'Role name already exists!',
                    'alert-type' => 'error'
                );
            } else {
                $role->update([
                    'name' => $request->name
                ]);
                $notification = array(
                    'message' => 'Role Updated Successfully',
                    'alert-type' => 'success'
                );
            }
        } else {
            $notification = array(
                'message' => 'Role name must be different from current name!',
                'alert-type' => 'warning'
            );
        }

        return redirect()->route('all.roles')->with($notification);
    }

    public function DeleteRole($id)
    {
        Role::find($id)->delete();
        $notification = array(
            'message' => 'Role Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function AddRolesPermission()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $permission_groups = User::getPermissionGroups();
        return view('admin.backend.pages.rolesetup.add_roles_permission', compact('roles', 'permission_groups', 'permissions'));
    }


    public function RolePermissionStore(Request $request)
    {
        $data = array();
        $permissions = $request->permission;

        foreach ($permissions as $key => $item) {
            $data[] = array(
                'role_id' => $request->role_id,
                'permission_id' => $item
            );
        }

        DB::table('role_has_permissions')->insert($data);

        $notification = array(
            'message' => 'Permission Assigned Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.roles.permission')->with($notification);
    }

    public function AllRolePermission()
    {
        $roles = Role::all();
        return view('admin.backend.pages.rolesetup.all_roles_permission', compact('roles'));
    }


    public function AdminEditRoles($id)
    {
        $role = Role::find($id);
        $permissions = Permission::all();
        $permission_groups = User::getPermissionGroups();

        return view('admin.backend.pages.rolesetup.edit_roles_permission', compact('role', 'permission_groups', 'permissions'));
    }

    public function AdminUpdateRoles(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $permissions = $request->input('permission', []);

        DB::beginTransaction();
        try {
            // Detach all permissions first
            $role->permissions()->detach();

            // Attach new permissions
            foreach ($permissions as $permissionId) {
                $permission = Permission::findOrFail($permissionId);
                $role->givePermissionTo($permission);
            }

            DB::commit();

            $notification = [
                'message' => 'Permissions Updated Successfully',
                'alert-type' => 'success'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating role permissions: ' . $e->getMessage());

            $notification = [
                'message' => 'Error updating permissions. Please try again.',
                'alert-type' => 'error'
            ];
        }

        return redirect()->route('all.roles.permission')->with($notification);
    }


    public function AdminDeleteRoles($id)
    {
        $role = Role::find($id);
        if (!is_null($role)) {
            $role->delete();
        }
        
        $notification = array(
            'message' => 'Role Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

}
