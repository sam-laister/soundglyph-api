import { redirect } from "@sveltejs/kit";
import type { PageServerLoad } from "./$types";
import { auth } from '@/api/auth.server';
import { toast } from "svelte-sonner";

export const load: PageServerLoad = async ({ cookies }) => {
    (await auth(cookies)).deleteTokens();

    toast.success('Logged out')
    redirect(303, '/auth/login');
}