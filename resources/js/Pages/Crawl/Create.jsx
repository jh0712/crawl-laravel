import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import UpdateProfileInformationForm from "@/Pages/Profile/Partials/UpdateProfileInformationForm";
import CrawlUrlForm from "@/Pages/Crawl/Partials/CrawlUrlFrom";

export default function Create({auth,error_message,success_message}) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Create</h2>}
        >
            <Head title="Profile" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <CrawlUrlForm
                            error_message={error_message}
                            success_message={success_message}
                        />
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
