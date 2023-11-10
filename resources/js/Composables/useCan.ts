import { usePage } from '@inertiajs/vue3';
import {computed, ComputedRef} from 'vue';
import type { Ref } from 'vue';
import type { Page, PageProps } from '@inertiajs/core';

export function useCan() {
  const page: Page<PageProps> = usePage();
  const user : ComputedRef<App.Models.User> = computed(() => page.props.auth.user as App.Models.User);
  const userPermissions = computed(() => user.value?.assigned_permissions);

  const can = (permission: string): boolean => {
    return Boolean(userPermissions.value?.includes(permission));
  };

  const canAny = (permissions: Array<string>): boolean => {
    return permissions.some((permission: string) => {
      return userPermissions.value?.includes(permission);
    });
  };

  const canAll = (permissions: Array<string>): boolean => {
    return permissions.every((permission: string) => {
      return userPermissions.value?.includes(permission);
    });
  };

  const canNot = (permission: string): boolean => {
    return !can(permission);
  };

  return { can, canNot, canAny, canAll };
}
