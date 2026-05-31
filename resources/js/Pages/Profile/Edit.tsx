import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import PageHeader from '@/Components/PageHeader';
import { PageProps } from '@/types';
import { Head } from '@inertiajs/react';
import DeleteUserForm from './Partials/DeleteUserForm';
import UpdatePasswordForm from './Partials/UpdatePasswordForm';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm';

export default function Edit({
    mustVerifyEmail,
    status,
}: PageProps<{ mustVerifyEmail: boolean; status?: string }>) {
    return (
        <AuthenticatedLayout>
            <Head title="Profile" />

            <div className="mx-auto max-w-3xl space-y-6">
                <PageHeader title="Profile" description="Manage your account information and security" />

                <section className="rounded-xl border border-border bg-card p-6 shadow-sm sm:p-8">
                    <UpdateProfileInformationForm
                        mustVerifyEmail={mustVerifyEmail}
                        status={status}
                    />
                </section>

                <section className="rounded-xl border border-border bg-card p-6 shadow-sm sm:p-8">
                    <UpdatePasswordForm />
                </section>

                <section className="rounded-xl border border-destructive/30 bg-destructive/5 p-6 shadow-sm sm:p-8">
                    <DeleteUserForm />
                </section>
            </div>
        </AuthenticatedLayout>
    );
}
