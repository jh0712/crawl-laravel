import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import CrawlDataTable from "@/Pages/Crawl/Partials/CrawlDataTable";

export default function Create({ auth ,datatable_url}) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">List</h2>}
        >
            <Head title="Profile" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                   <CrawlDataTable
                       datatable_url={datatable_url}
                   />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
