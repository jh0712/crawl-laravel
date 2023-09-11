import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import EditForm from "@/Pages/Crawl/Partials/EditForm";

export default function Success({auth,crawledResult}) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Crawl Success Page</h2>}
        >
            <Head title="Crawl Success Page" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <h1>Crawl Success Page</h1>
                        <EditForm
                            crawledResult = {crawledResult}
                            type='success'
                        />
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
