import AuthenticatedLayout from '@/layouts/authenticated-layout';
import DeleteUserForm from '@/pages/profile/partials/delete-user-form';
import UpdatePasswordForm from '@/pages/profile/partials/update-password-form';
import UpdateProfileInformationForm from '@/pages/profile/partials/update-profile-information-form';
import { PageProps } from '@/types';
import { Head } from '@inertiajs/react';

export default function Edit({
    mustVerifyEmail,
    status,
}: PageProps<{ mustVerifyEmail: boolean; status?: string }>) {
    return (
        <AuthenticatedLayout>
            <Head title="Profile" />

            <div className="bg-gray-900 p-8 text-white">
                <h1 className="text-center text-4xl font-bold">Edit Profile</h1>

                <div className="pb-12">
                    <div className="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                        <div className="p-4 sm:p-8">
                            <UpdateProfileInformationForm
                                mustVerifyEmail={mustVerifyEmail}
                                status={status}
                                className="max-w-xl"
                            />
                        </div>

                        <div className="border-t border-gray-800 pb-4 pt-8 sm:p-8">
                            <UpdatePasswordForm className="max-w-xl" />
                        </div>

                        <div className="border-t border-gray-800 pb-4 pt-8 sm:p-8">
                            <DeleteUserForm className="max-w-xl" />
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
