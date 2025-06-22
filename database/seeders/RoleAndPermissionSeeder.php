<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissions = [
            // Roles permissions
            ['name' => 'roles-search', 'guard_name' => 'web', 'display_name' => 'Role Search'],
            ['name' => 'roles-list', 'guard_name' => 'web', 'display_name' => 'Role List'],
            ['name' => 'roles-create', 'guard_name' => 'web', 'display_name' => 'Role Create'],
            ['name' => 'roles-edit', 'guard_name' => 'web', 'display_name' => 'Role Edit'],
            ['name' => 'roles-store', 'guard_name' => 'web', 'display_name' => 'Role Store'],
            ['name' => 'roles-update', 'guard_name' => 'web', 'display_name' => 'Role Update'],
            ['name' => 'roles-show', 'guard_name' => 'web', 'display_name' => 'Role View'],
            ['name' => 'roles-delete', 'guard_name' => 'web', 'display_name' => 'Role Delete'],

            // Permissions permissions
            ['name' => 'permissions-search', 'guard_name' => 'web', 'display_name' => 'Permissions Search'],
            ['name' => 'permissions-list', 'guard_name' => 'web', 'display_name' => 'Permissions List'],
            ['name' => 'permissions-create', 'guard_name' => 'web', 'display_name' => 'Permissions Create'],
            ['name' => 'permissions-edit', 'guard_name' => 'web', 'display_name' => 'Permissions Edit'],
            ['name' => 'permissions-store', 'guard_name' => 'web', 'display_name' => 'Permissions Store'],
            ['name' => 'permissions-update', 'guard_name' => 'web', 'display_name' => 'Permissions Update'],
            ['name' => 'permissions-show', 'guard_name' => 'web', 'display_name' => 'Permissions Show'],
            ['name' => 'permissions-delete', 'guard_name' => 'web', 'display_name' => 'Permissions Delete'],

            // Users permissions
            ['name' => 'users-search', 'guard_name' => 'web', 'display_name' => 'Users Search'],
            ['name' => 'users-list', 'guard_name' => 'web', 'display_name' => 'Users List'],
            ['name' => 'users-create', 'guard_name' => 'web', 'display_name' => 'Users Create'],
            ['name' => 'users-edit', 'guard_name' => 'web', 'display_name' => 'Users Edit'],
            ['name' => 'users-store', 'guard_name' => 'web', 'display_name' => 'Users Store'],
            ['name' => 'users-update', 'guard_name' => 'web', 'display_name' => 'Users Update'],
            ['name' => 'users-show', 'guard_name' => 'web', 'display_name' => 'Users Show'],
            ['name' => 'users-active', 'guard_name' => 'web', 'display_name' => 'Users Active'],
            ['name' => 'users-deactive', 'guard_name' => 'web', 'display_name' => 'Users Deactive'],
            ['name' => 'users-delete', 'guard_name' => 'web', 'display_name' => 'Users Delete'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name'], 'guard_name' => $permission['guard_name']],
                ['display_name' => $permission['display_name']]
            );
        }

        // Repeat similar for the rest of the permissions



        $permissions = [
            // New set of permissions (Company, Division, Expense Type)
            ['name' => 'company-list', 'display_name' => 'Company Master List'],
            ['name' => 'company-create', 'display_name' => 'Company Master Create'],
            ['name' => 'company-edit', 'display_name' => 'Company Master Edit'],
            ['name' => 'company-store', 'display_name' => 'Company Master Store'],
            ['name' => 'company-update', 'display_name' => 'Company Master Update'],
            ['name' => 'company-show', 'display_name' => 'Company Master Show'],
            ['name' => 'company-delete', 'display_name' => 'Company Master Delete'],
            ['name' => 'company-search', 'display_name' => 'Company Master Search'],

            ['name' => 'divison-list', 'display_name' => 'Divison Master List'],
            ['name' => 'divison-create', 'display_name' => 'Divison Master Create'],
            ['name' => 'divison-edit', 'display_name' => 'Divison Master Edit'],
            ['name' => 'divison-store', 'display_name' => 'Divison Master Store'],
            ['name' => 'divison-update', 'display_name' => 'Divison Master Update'],
            ['name' => 'divison-show', 'display_name' => 'Divison Master Show'],
            ['name' => 'divison-delete', 'display_name' => 'Divison Master Delete'],
            ['name' => 'divison-search', 'display_name' => 'Divison Master Search'],

            ['name' => 'expense-type-list', 'display_name' => 'Expense Type Master List'],
            ['name' => 'expense-type-create', 'display_name' => 'Expense Type Master Create'],
            ['name' => 'expense-type-edit', 'display_name' => 'Expense Type Master Edit'],
            ['name' => 'expense-type-store', 'display_name' => 'Expense Type Master Store'],
            ['name' => 'expense-type-update', 'display_name' => 'Expense Type Master Update'],
            ['name' => 'expense-type-show', 'display_name' => 'Expense Type Master Show'],
            ['name' => 'expense-type-delete', 'display_name' => 'Expense Type Master Delete'],
            ['name' => 'expense-type-search', 'display_name' => 'Expense Type Master Search'],


            ['name' => 'location-list', 'display_name' => 'Location Master List'],
            ['name' => 'location-create', 'display_name' => 'Location Master Create'],
            ['name' => 'location-edit', 'display_name' => 'Location Master Edit'],
            ['name' => 'location-store', 'display_name' => 'Location Master Store'],
            ['name' => 'location-update', 'display_name' => 'Location Type Master Update'],
            ['name' => 'location-show', 'display_name' => 'Location Master Show'],
            ['name' => 'location-delete', 'display_name' => 'Location Master Delete'],
            ['name' => 'location-search', 'display_name' => 'Location Master Search'],
            
            ['name' => 'mode-of-expense-list', 'display_name' => 'Mode Of Expense Master List'],
            ['name' => 'mode-of-expense-create', 'display_name' => 'Mode Of Expense Master Create'],
            ['name' => 'mode-of-expense-edit', 'display_name' => 'Mode Of Expense Master Edit'],
            ['name' => 'mode-of-expense-store', 'display_name' => 'Mode Of Expense Master Store'],
            ['name' => 'mode-of-expense-update', 'display_name' => 'Mode Of Expense Type Master Update'],
            ['name' => 'mode-of-expense-show', 'display_name' => 'Mode Of Expense Master Show'],
            ['name' => 'mode-of-expense-delete', 'display_name' => 'Mode Of Expense Master Delete'],
            ['name' => 'mode-of-expense-search', 'display_name' => 'Mode Of Expense Master Search'],
            
            ['name' => 'other-expense-list', 'display_name' => 'Other Expense Master List'],
            ['name' => 'other-expense-create', 'display_name' => 'Other Expense Master Create'],
            ['name' => 'other-expense-edit', 'display_name' => 'Other Expense Master Edit'],
            ['name' => 'other-expense-store', 'display_name' => 'Other Expense Master Store'],
            ['name' => 'other-expense-update', 'display_name' => 'Other Expense Type Master Update'],
            ['name' => 'other-expense-show', 'display_name' => 'Other Expense Master Show'],
            ['name' => 'other-expense-delete', 'display_name' => 'Other Expense Master Delete'],
            ['name' => 'other-expense-search', 'display_name' => 'Other Expense Master Search'],
            
            ['name' => 'rejection-list', 'display_name' => 'Rejection Master List'],
            ['name' => 'rejection-create', 'display_name' => 'Rejection Master Create'],
            ['name' => 'rejection-edit', 'display_name' => 'Rejection Master Edit'],
            ['name' => 'rejection-store', 'display_name' => 'Rejection Master Store'],
            ['name' => 'rejection-update', 'display_name' => 'Rejection Type Master Update'],
            ['name' => 'rejection-show', 'display_name' => 'Rejection Master Show'],
            ['name' => 'rejection-delete', 'display_name' => 'Rejection Master Delete'],
            ['name' => 'rejection-search', 'display_name' => 'Rejection Master Search'],
            
            ['name' => 're-open-list', 'display_name' => 'Re Open Master List'],
            ['name' => 're-open-create', 'display_name' => 'Re Open Master Create'],
            ['name' => 're-open-edit', 'display_name' => 'Re Open Master Edit'],
            ['name' => 're-open-store', 'display_name' => 'Re Open Master Store'],
            ['name' => 're-open-update', 'display_name' => 'Re Open Type Master Update'],
            ['name' => 're-open-show', 'display_name' => 'Re Open Master Show'],
            ['name' => 're-open-delete', 'display_name' => 'Re Open Master Delete'],
            ['name' => 're-open-search', 'display_name' => 'Re Open Master Search'],
            
            ['name' => 'sales-list', 'display_name' => 'Sales Master List'],
            ['name' => 'sales-create', 'display_name' => 'Sales Master Create'],
            ['name' => 'sales-edit', 'display_name' => 'Sales Master Edit'],
            ['name' => 'sales-store', 'display_name' => 'Sales Master Store'],
            ['name' => 'sales-update', 'display_name' => 'Sales Type Master Update'],
            ['name' => 'sales-show', 'display_name' => 'Sales Master Show'],
            ['name' => 'sales-delete', 'display_name' => 'Sales Master Delete'],
            ['name' => 'sales-search', 'display_name' => 'Sales Master Search'],
            
            ['name' => 'way-of-location-list', 'display_name' => 'Way Of Location Master List'],
            ['name' => 'way-of-location-create', 'display_name' => 'Way Of Location Master Create'],
            ['name' => 'way-of-location-edit', 'display_name' => 'Way Of Location Way Of Location Edit'],
            ['name' => 'way-of-location-store', 'display_name' => 'Way Of Location Master Store'],
            ['name' => 'way-of-location-update', 'display_name' => 'Way Of Location Type Master Update'],
            ['name' => 'way-of-location-show', 'display_name' => 'Way Of Location Master Show'],
            ['name' => 'way-of-location-delete', 'display_name' => 'Way Of Location Master Delete'],
            ['name' => 'way-of-location-search', 'display_name' => 'Way Of Location Master Search'],
            
            ['name' => 'city-list', 'display_name' => 'City List'],
            ['name' => 'city-create', 'display_name' => 'City Create'],
            ['name' => 'city-edit', 'display_name' => 'City Edit'],
            ['name' => 'city-store', 'display_name' => 'City Store'],
            ['name' => 'city-update', 'display_name' => 'City Update'],
            ['name' => 'city-show', 'display_name' => 'City Show'],
            ['name' => 'city-delete', 'display_name' => 'City Delete'],
            ['name' => 'city-search', 'display_name' => 'City Search'],
            
            ['name' => 'state-list', 'display_name' => 'State List'],
            ['name' => 'state-create', 'display_name' => 'State Create'],
            ['name' => 'state-edit', 'display_name' => 'State Edit'],
            ['name' => 'state-store', 'display_name' => 'State Store'],
            ['name' => 'state-update', 'display_name' => 'State Update'],
            ['name' => 'state-show', 'display_name' => 'State Show'],
            ['name' => 'state-delete', 'display_name' => 'State Delete'],
            ['name' => 'state-search', 'display_name' => 'State Search'],
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::updateOrCreate(
                ['name' => $permission['name'], 'guard_name' => 'web'], // Matching condition
                ['display_name' => $permission['display_name']] // Update or create
            );
        }



        $permissions = [
            ['name' => 'hod-list', 'guard_name' => 'web', 'display_name' => 'Hod Master List'],
            ['name' => 'hod-create', 'guard_name' => 'web', 'display_name' => 'Hod Master Create'],
            ['name' => 'hod-edit', 'guard_name' => 'web', 'display_name' => 'Hod Master Edit'],
            ['name' => 'hod-store', 'guard_name' => 'web', 'display_name' => 'Hod Master Store'],
            ['name' => 'hod-update', 'guard_name' => 'web', 'display_name' => 'Hod Type Master Update'],
            ['name' => 'hod-show', 'guard_name' => 'web', 'display_name' => 'Hod Master Show'],
            ['name' => 'hod-delete', 'guard_name' => 'web', 'display_name' => 'Hod Master Delete'],
            ['name' => 'hod-search', 'guard_name' => 'web', 'display_name' => 'Hod Master Search']
        ];
        foreach ($permissions as $permission) {
            Permission::updateOrCreate($permission);
        }


        $permissions = [
            ['name' => 'designation-list', 'guard_name' => 'web', 'display_name' => 'Designation Master List'],
            ['name' => 'designation-create', 'guard_name' => 'web', 'display_name' => 'Designation Master Create'],
            ['name' => 'designation-edit', 'guard_name' => 'web', 'display_name' => 'Designation Master Edit'],
            ['name' => 'designation-store', 'guard_name' => 'web', 'display_name' => 'Designation Master Store'],
            ['name' => 'designation-update', 'guard_name' => 'web', 'display_name' => 'Designation Type Master Update'],
            ['name' => 'designation-show', 'guard_name' => 'web', 'display_name' => 'Designation Master Show'],  // <-- Remove the comma here
            ['name' => 'designation-delete', 'guard_name' => 'web', 'display_name' => 'Designation Master Delete'],
            ['name' => 'designation-search', 'guard_name' => 'web', 'display_name' => 'Designation Master Search']
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate($permission);
        }

        $permissions = [
            ['name' => 'policy', 'guard_name' => 'web', 'display_name' => 'Policy All'],
            ['name' => 'policy-setting-list', 'guard_name' => 'web', 'display_name' => 'Policy Setting List'],
            ['name' => 'policy-setting-create', 'guard_name' => 'web', 'display_name' => 'Policy Setting Create'],
            ['name' => 'policy-setting-edit', 'guard_name' => 'web', 'display_name' => 'Policy Setting Edit'],
            ['name' => 'policy-setting-store', 'guard_name' => 'web', 'display_name' => 'Policy Setting Store'],
            ['name' => 'policy-setting-update', 'guard_name' => 'web', 'display_name' => 'Policy Setting Update'],
            ['name' => 'policy-setting-show', 'guard_name' => 'web', 'display_name' => 'Policy Setting Show'],  // <-- Remove the comma here
            ['name' => 'policy-setting-delete', 'guard_name' => 'web', 'display_name' => 'Policy Setting Delete'],
            ['name' => 'policy-setting-search', 'guard_name' => 'web', 'display_name' => 'Policy Setting Search']
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate($permission);
        }

        $permissions = [
            ['name' => 'policy-guidelines-list', 'guard_name' => 'web', 'display_name' => 'Policy Guidelines List'],
            ['name' => 'policy-guidelines-create', 'guard_name' => 'web', 'display_name' => 'Policy Guidelines Create'],
            ['name' => 'policy-guidelines-edit', 'guard_name' => 'web', 'display_name' => 'Policy Guidelines Edit'],
            ['name' => 'policy-guidelines-store', 'guard_name' => 'web', 'display_name' => 'Policy Guidelines Store'],
            ['name' => 'policy-guidelines-update', 'guard_name' => 'web', 'display_name' => 'Policy Guidelines Update'],
            ['name' => 'policy-guidelines-show', 'guard_name' => 'web', 'display_name' => 'Policy Guidelines Show'],  // <-- Remove the comma here
            ['name' => 'policy-guidelines-delete', 'guard_name' => 'web', 'display_name' => 'Policy Guidelines Delete'],
            ['name' => 'policy-guidelines-search', 'guard_name' => 'web', 'display_name' => 'Policy Guidelines Search']
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate($permission);
        }

        $permissions = [
            ['name' => 'calc', 'guard_name' => 'web', 'display_name' => 'HOD Calc'],
            ['name' => 'process-time-report', 'guard_name' => 'web', 'display_name' => 'Process Time Report'],
            ['name' => 'status-of-expense', 'guard_name' => 'web', 'display_name' => 'Status Of Expenses'],
            ['name' => 'monthly-expense', 'guard_name' => 'web', 'display_name' => 'Montly Expenses'],
            ['name' => 'expense-pending-for-approval', 'guard_name' => 'web', 'display_name' => 'Expense Pending For Approval'], 
            
            ['name' => 'process-time-report', 'guard_name' => 'web', 'display_name' => 'Process Time Report'],
            ['name' => 'sla-report', 'guard_name' => 'web', 'display_name' => 'SLA Report'], 
            ['name' => 'expense-slip-report', 'guard_name' => 'web', 'display_name' => 'Expense Slip Report'],

            ['name' => 'multi-expense-slip-report', 'guard_name' => 'web', 'display_name' => 'Multi Expense Slip Report'],
            ['name' => 'expense-details-report', 'guard_name' => 'web', 'display_name' => 'Expense Details Report'],
            ['name' => 'process-report', 'guard_name' => 'web', 'display_name' => 'Process Report'],
            ['name' => 'genex-report', 'guard_name' => 'web', 'display_name' => 'GenEx Report'],            
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate($permission);
        }

        $permissions = [
            ['name' => 'sale-pax', 'guard_name' => 'web', 'display_name' => 'Sale PAX'], 
            ['name' => 'sale-fare', 'guard_name' => 'web', 'display_name' => 'Sale Fare'], 
            ['name' => 'sale-total-expense', 'guard_name' => 'web', 'display_name' => 'Sale Total Expense'], 
            ['name' => 'se-analysis', 'guard_name' => 'web', 'display_name' => 'SE Analysis'],
            ['name' => 'sale-expense', 'guard_name' => 'web', 'display_name' => 'Sale Expense'],
            ['name' => 'sale-expenses', 'guard_name' => 'web', 'display_name' => 'Sale Expenses'],

            ['name' => 'general-expenses', 'guard_name' => 'web', 'display_name' => 'General Expense'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate($permission);
        }

        $permissions = [
            ['name' => 'monthly-expense-list', 'guard_name' => 'web', 'display_name' => 'Monthly Expense List'],
            ['name' => 'monthly-expense-create', 'guard_name' => 'web', 'display_name' => 'Monthly Expense Create'],
            ['name' => 'monthly-expense-edit', 'guard_name' => 'web', 'display_name' => 'Monthly Expense Edit'],
            ['name' => 'monthly-expense-store', 'guard_name' => 'web', 'display_name' => 'Monthly Expense Store'],
            ['name' => 'monthly-expense-update', 'guard_name' => 'web', 'display_name' => 'Monthly Expense Update'],
            ['name' => 'monthly-expense-show', 'guard_name' => 'web', 'display_name' => 'Monthly Expense Show'],  // <-- Remove the comma here
            ['name' => 'monthly-expense-delete', 'guard_name' => 'web', 'display_name' => 'Monthly Expense Delete'],
            ['name' => 'monthly-expense-search', 'guard_name' => 'web', 'display_name' => 'Monthly Expense Search']
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate($permission);
        } 

        $permissions = [
            ['name' => 'monthly-pending-expense-list', 'guard_name' => 'web', 'display_name' => 'Monthly Pending Expense List'],
            ['name' => 'monthly-pending-expense-create', 'guard_name' => 'web', 'display_name' => 'Monthly Pending Expense Create'],
            ['name' => 'monthly-pending-expense-edit', 'guard_name' => 'web', 'display_name' => 'Monthly Pending Expense Edit'],
            ['name' => 'monthly-pending-expense-store', 'guard_name' => 'web', 'display_name' => 'Monthly Pending Expense Store'],
            ['name' => 'monthly-pending-expense-update', 'guard_name' => 'web', 'display_name' => 'Monthly Pending Expense Update'],
            ['name' => 'monthly-pending-expense-show', 'guard_name' => 'web', 'display_name' => 'Monthly Pending Expense Show'],
            ['name' => 'monthly-pending-expense-delete', 'guard_name' => 'web', 'display_name' => 'Monthly Pending Expense Delete'],
            ['name' => 'monthly-pending-expense-search', 'guard_name' => 'web', 'display_name' => 'Monthly Pending Expense Search'],

            ['name' => 'monthly-pending-expense-reopen', 'guard_name' => 'web', 'display_name' => 'Monthly Pending Expense Re-Open'],
            ['name' => 'monthly-pending-expense-reject', 'guard_name' => 'web', 'display_name' => 'Monthly Pending Expense Reject'],
            ['name' => 'monthly-pending-expense-verify', 'guard_name' => 'web', 'display_name' => 'Monthly Pending Expense Verify'],
            ['name' => 'monthly-pending-expense-approve', 'guard_name' => 'web', 'display_name' => 'Monthly Pending Expense Approve'],

            ['name' => 'attendance-list', 'guard_name' => 'web', 'display_name' => 'Attendance List'],
            ['name' => 'attendance-create', 'guard_name' => 'web', 'display_name' => 'Attendance Create'],
            ['name' => 'attendance-edit', 'guard_name' => 'web', 'display_name' => 'Attendance Edit'],
            ['name' => 'attendance-store', 'guard_name' => 'web', 'display_name' => 'Attendance Store'],
            ['name' => 'attendance-update', 'guard_name' => 'web', 'display_name' => 'Attendance Update'],
            ['name' => 'attendance-show', 'guard_name' => 'web', 'display_name' => 'Attendance Show'],
            ['name' => 'attendance-delete', 'guard_name' => 'web', 'display_name' => 'Attendance Delete'],
            ['name' => 'attendance-search', 'guard_name' => 'web', 'display_name' => 'Attendance Search']
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate($permission);
        }




        // Create roles and assign created permissions
        $role = Role::updateOrCreate(['name' => 'Super Admin']);
        // $role->givePermissionTo(Permission::all());
        $role->givePermissionTo(['roles-search',
                                    'roles-list',
                                    'roles-create',
                                    'roles-edit',
                                    'roles-store',
                                    'roles-update',
                                    'roles-show',
                                    'roles-delete',
                                    
                                    'permissions-search',
                                    'permissions-list',
                                    'permissions-create',
                                    'permissions-edit',
                                    'permissions-store',
                                    'permissions-update',
                                    'permissions-show',
                                    'permissions-delete',
                                    
                                    'users-search',
                                    'users-list',
                                    'users-create',
                                    'users-edit',
                                    'users-store',
                                    'users-update',
                                    'users-show',
                                    'users-active',
                                    'users-deactive',
                                    'users-delete',


                                        'company-list',
                                        'company-create',
                                        'company-edit',
                                        'company-store',
                                        'company-update',
                                        'company-show',
                                        'company-delete',
                                        'company-search',
                                        
                                        'divison-list',
                                        'divison-create',
                                        'divison-edit',
                                        'divison-store',
                                        'divison-update',
                                        'divison-show',
                                        'divison-delete',
                                        'divison-search',
                                        
                                        'expense-type-list',
                                        'expense-type-create',
                                        'expense-type-edit',
                                        'expense-type-store',
                                        'expense-type-update',
                                        'expense-type-show',
                                        'expense-type-delete',
                                        'expense-type-search',
                                        
                                        'location-list',
                                        'location-create',
                                        'location-edit',
                                        'location-store',
                                        'location-update',
                                        'location-show',
                                        'location-delete',
                                        'location-search',
                                        
                                        'mode-of-expense-list',
                                        'mode-of-expense-create',
                                        'mode-of-expense-edit',
                                        'mode-of-expense-store',
                                        'mode-of-expense-update',
                                        'mode-of-expense-show',
                                        'mode-of-expense-delete',
                                        'mode-of-expense-search',
                                        
                                        'other-expense-list',
                                        'other-expense-create',
                                        'other-expense-edit',
                                        'other-expense-store',
                                        'other-expense-update',
                                        'other-expense-show',
                                        'other-expense-delete',
                                        'other-expense-search',
                                        
                                        'rejection-list',
                                        'rejection-create',
                                        'rejection-edit',
                                        'rejection-store',
                                        'rejection-update',
                                        'rejection-show',
                                        'rejection-delete',
                                        'rejection-search',
                                        
                                        're-open-list',
                                        're-open-create',
                                        're-open-edit',
                                        're-open-store',
                                        're-open-update',
                                        're-open-show',
                                        're-open-delete',
                                        're-open-search',
                                        
                                        'sales-list',
                                        'sales-create',
                                        'sales-edit',
                                        'sales-store',
                                        'sales-update',
                                        'sales-show',
                                        'sales-delete',
                                        'sales-search',
                                        
                                        'way-of-location-list',
                                        'way-of-location-create',
                                        'way-of-location-edit',
                                        'way-of-location-store',
                                        'way-of-location-update',
                                        'way-of-location-show',
                                        'way-of-location-delete',
                                        'way-of-location-search',
                                        
                                        'city-list',
                                        'city-create',
                                        'city-edit',
                                        'city-store',
                                        'city-update',
                                        'city-show',
                                        'city-delete',
                                        'city-search',
                                        
                                        'state-list',
                                        'state-create',
                                        'state-edit',
                                        'state-store',
                                        'state-update',
                                        'state-show',
                                        'state-delete',
                                        'state-search',

                                        'hod-list',
                                        'hod-create',
                                        'hod-edit',
                                        'hod-store',
                                        'hod-update',
                                        'hod-show',
                                        'hod-delete',
                                        'hod-search',
                                        
                                        'designation-list',
                                        'designation-create',
                                        'designation-edit',
                                        'designation-store',
                                        'designation-update',
                                        'designation-show',
                                        'designation-delete',
                                        'designation-search',

                                        'policy',
                                        'policy-setting-list',
                                        'policy-setting-create',
                                        'policy-setting-edit',
                                        'policy-setting-store',
                                        'policy-setting-update',
                                        'policy-setting-show',
                                        'policy-setting-delete',
                                        'policy-setting-search',

                                        'policy-guidelines-list',
                                        'policy-guidelines-create',
                                        'policy-guidelines-edit',
                                        'policy-guidelines-store',
                                        'policy-guidelines-update',
                                        'policy-guidelines-show',
                                        'policy-guidelines-delete',
                                        'policy-guidelines-search',

                                        

                                        'process-time-report',
                                        'sla-report',
                                        'status-of-expense',


                                ]);


        $role = Role::updateOrCreate(['name' => 'Sales Admin Hod']);
        $role->givePermissionTo(['calc', 'process-time-report', 'status-of-expense', 'expense-pending-for-approval', 'sla-report', 'expense-slip-report', 'multi-expense-slip-report', 'expense-details-report', 'process-report', 'genex-report', 'sale-pax', 'sale-fare', 'sale-total-expense', 'se-analysis', 'sale-expense', 'sale-expenses', 'general-expenses', 'monthly-pending-expense-list', 'monthly-pending-expense-create', 'monthly-pending-expense-edit', 'monthly-pending-expense-store', 'monthly-pending-expense-update', 'monthly-pending-expense-show', 'monthly-pending-expense-delete', 'monthly-pending-expense-search', 'monthly-pending-expense-reopen', 'monthly-pending-expense-reject', 'monthly-pending-expense-approve', 'attendance-list', 'attendance-create', 'attendance-edit', 'attendance-store', 'attendance-update', 'attendance-show', 'attendance-delete', 'attendance-search']);

        

        $role = Role::updateOrCreate(['name' => 'Sales Admin']);
        $role->givePermissionTo(['process-time-report', 'status-of-expense', 'expense-pending-for-approval', 'sla-report', 'expense-slip-report', 'multi-expense-slip-report', 'attendance-list', 'attendance-create', 'attendance-edit', 'attendance-store', 'attendance-update', 'attendance-show', 'attendance-delete', 'attendance-search', 'monthly-pending-expense-list', 'monthly-pending-expense-create', 'monthly-pending-expense-edit', 'monthly-pending-expense-store', 'monthly-pending-expense-update', 'monthly-pending-expense-show', 'monthly-pending-expense-delete', 'monthly-pending-expense-search', 'monthly-pending-expense-reopen', 'monthly-pending-expense-reject', 'monthly-pending-expense-verify']);

        $role = Role::updateOrCreate(['name' => 'Sales']);
        $role->givePermissionTo(['status-of-expense', 'monthly-expense', 'process-time-report']);



        $superadmin = \App\Models\User::updateOrCreate(
            ['email' => 'superadmin@gmail.com'], // search criteria
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'status' => '1',
                'password' => Hash::make(12345678),
            ]
        );
        // Check if the User already has the 'Super Admin' role
        if (!$superadmin->hasRole('Super Admin')) {
            $superadmin->syncRoles('Super Admin');
        }

        


        //  create Sales Admin Hod and assign role
        $sales_admin_hod = \App\Models\User::updateOrCreate(
            ['email' => 'salesadminhod@gmail.com'], // search criteria
            [
                'name' => 'Sales Admin Hod',
                'email' => 'salesadminhod@gmail.com',
                'status' => '1',
                'password' => Hash::make(12345678),
            ]
        );
        // Check if the User already has the 'Branch Owner' role
        if (!$sales_admin_hod->hasRole('Sales Admin Hod')) {
            $sales_admin_hod->syncRoles('Sales Admin Hod');
        }



        //  create Sales Admin and assign role
        $sales_admin = \App\Models\User::updateOrCreate(
            ['email' => 'salesadmin@gmail.com'], // search criteria
            [
                'name' => 'Sales Admin',
                'email' => 'salesadmin@gmail.com',
                'status' => '1',
                'password' => Hash::make(12345678),
            ]
        );
        // Check if the User already has the 'Branch Owner' role
        if (!$sales_admin->hasRole('Sales Admin')) {
            $sales_admin->syncRoles('Sales Admin');
        }



        //  create Sales and assign role
        $staff = \App\Models\User::updateOrCreate(
            ['email' => 'Sales@gmail.com'], // search criteria
            [
                'name' => 'Sales',
                'email' => 'Sales@gmail.com',
                'status' => '1',
                'password' => Hash::make(12345678),
            ]
        );
         // Check if the User already has the 'Staff' role
        if (!$staff->hasRole('Sales')) {
            $staff->syncRoles('Sales');
        }
        

        
    }
}
