import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import EditForm from "@/Pages/Crawl/Partials/EditForm";
import CrawlUrlForm from "@/Pages/Crawl/Partials/CrawlUrlFrom";

export default function Edit({auth,crawledResult,success_message,error_message}) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Crawl Detail Page</h2>}
        >
            <Head title="Crawl Detail Page" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <h1>Crawl Detail Page</h1>
                        <EditForm
                            crawledResult = {crawledResult}
                            type='edit'
                            error_message={error_message}
                            success_message={success_message}
                        />
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
