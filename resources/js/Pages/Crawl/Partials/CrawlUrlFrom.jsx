import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { Link, useForm, usePage } from '@inertiajs/react';
import { Transition } from '@headlessui/react';
export default function CrawlUrlForm({ className = '' }) {

    const user = usePage().props.auth.user;

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        name: user.name,
        email: user.email
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('crawl-management.store'));
    };

    return (
        <section className={className}>
            <header>
                <h2 className="text-lg font-medium text-gray-900">Crawl by Url</h2>

                <p className="mt-1 text-sm text-gray-600">
                    Input a url path what you want
                </p>
            </header>

            <form onSubmit={submit} className="mt-6 space-y-6">
                <div>
                    <InputLabel htmlFor="url_path" value="url_path" />

                    <TextInput
                        id="url_path"
                        className="mt-1 block w-full"
                        onChange={(e) => setData('url_path', e.target.value)}
                        required
                        isFocused
                        autoComplete="url_path"
                        defaultValue='https://google.com'
                    />

                    <InputError className="mt-2" message={errors.url_path} />
                </div>
                <div className="flex items-center gap-4">
                    <PrimaryButton disabled={processing}>Search</PrimaryButton>

                    <Transition
                        show={recentlySuccessful}
                        enter="transition ease-in-out"
                        enterFrom="opacity-0"
                        leave="transition ease-in-out"
                        leaveTo="opacity-0"
                    >
                        <p className="text-sm text-gray-600">search done.</p>
                    </Transition>
                </div>
            </form>
        </section>
    );
}
