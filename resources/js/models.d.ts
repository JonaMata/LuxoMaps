/**
 * This file is auto generated using 'php artisan typescript:generate'
 *
 * Changes to this file will be lost when the command is run again
 */

declare namespace App.Models {
    export interface Peertje {
        id: number;
        name: string;
        created_at: string | null;
        updated_at: string | null;
        api_id: number | null;
        locations?: Array<App.Models.PeertjeLocation> | null;
        locations_count?: number | null;
        readonly locations?: any;
    }

    export interface PeertjeLocation {
        id: number;
        latitude: number;
        longitude: number;
        peertje_id: number;
        created_at: string | null;
        updated_at: string | null;
        battery_percentage: number | null;
        peertje?: App.Models.Peertje | null;
    }

    export interface User {
        id: number;
        name: string;
        email: string;
        email_verified_at: string | null;
        password: string;
        remember_token: string | null;
        created_at: string | null;
        updated_at: string | null;
        readonly assigned_roles?: Array<any>;
        readonly assigned_permissions?: Array<any>;
    }

    export interface Role {
        id: number;
        name: string;
        guard_name: string;
        created_at: string | null;
        updated_at: string | null;
        description: string | null;
        permissions?: Array<App.Models.Permission> | null;
        users?: Array<App.Models.User> | null;
        permissions_count?: number | null;
        users_count?: number | null;
        readonly permissions?: any;
    }

    export interface Permission {
        id: number;
        name: string;
        guard_name: string;
        created_at: string | null;
        updated_at: string | null;
        description: string | null;
        roles?: Array<App.Models.Role> | null;
        users?: Array<App.Models.User> | null;
        permissions?: Array<App.Models.Permission> | null;
        roles_count?: number | null;
        users_count?: number | null;
        permissions_count?: number | null;
    }

    export interface Sticker {
        id: number;
        latitude: number;
        longitude: number;
        user_id: number;
        created_at: string | null;
        updated_at: string | null;
        user?: App.Models.User | null;
        readonly owner?: any;
        readonly is_owner?: any;
        readonly is_peertje?: any;
    }

}
