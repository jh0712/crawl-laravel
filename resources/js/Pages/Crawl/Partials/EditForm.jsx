import { useEffect } from 'react';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import TextArea from '@/Components/TextArea';
import { Link, useForm, usePage } from '@inertiajs/react';
import { Transition } from '@headlessui/react';

export default function EditForm({error_message =null ,success_message=null,crawledResult,type}) {

    const user = usePage().props.auth.user;

    const { data, setData, post, errors, processing, recentlySuccessful,reset } = useForm({
        title:crawledResult.title,
        description:crawledResult.description,
        body:crawledResult.body,
        url:crawledResult.url,
        created_at:crawledResult.created_at,
    });

    useEffect(() => {
        return () => {
            reset();
        };
    }, []);
    const submit = (e) => {
        e.preventDefault();

        post(route('crawl-management.edit'));
    };
    const imageUrl = route('document-management.document_id.index',crawledResult.documents.id);

    return (
        <section>
            {
                error_message &&
                <Transition
                    show={recentlySuccessful}
                    enter="transition ease-in-out"
                    enterFrom="opacity-0"
                    leave="transition ease-in-out"
                    leaveTo="opacity-0"
                >
                    <div className="mb-4 font-medium text-sm text-red-600">{error_message}</div>
                </Transition>
            }
            {
                success_message &&
                <Transition
                    show={recentlySuccessful}
                    enter="transition ease-in-out"
                    enterFrom="opacity-0"
                    leave="transition ease-in-out"
                    leaveTo="opacity-0"
                >
                    <div className="mb-4 font-medium text-sm text-green-600">{success_message}</div>
                </Transition>
            }
            <form onSubmit={submit} className="mt-6 space-y-6">
                <h1>Screenshot</h1>
                <img src={imageUrl} alt=""/>
                <h1>連結：<a href={data.url} target="_blank" title={data.title}>{data.title}</a></h1>
                <div>
                    <InputLabel htmlFor="title" value="title" />
                    <TextInput
                        id="title"
                        name="title"
                        value={data.title}
                        className="mt-1 block w-full"
                        autoComplete="title"
                        isFocused={true}
                        onChange={(e) => setData('title', e.target.value)}
                        {...(type === 'success' ? { disabled: true } : {})}
                        placeholder={'https://google.ocm'}
                    />
                    <InputError className="mt-2" message={errors.title} />
                </div>
                <div>
                    <InputLabel htmlFor="url" value="url" />
                    <TextInput
                        id="url"
                        name="url"
                        value={data.url}
                        className="mt-1 block w-full"
                        autoComplete="url"
                        isFocused={true}
                        onChange={(e) => setData('url', e.target.value)}
                        {...(type === 'success' ? { disabled: true } : {})}
                    />

                    <InputError className="mt-2" message={errors.url} />
                </div>
                <div>
                    <InputLabel htmlFor="description" value="description" />
                    <TextInput
                        id="description"
                        name="description"
                        value={data.description}
                        className="mt-1 block w-full"
                        autoComplete="description"
                        isFocused={true}
                        onChange={(e) => setData('description', e.target.value)}
                        {...(type === 'success' ? { disabled: true } : {})}
                    />

                    <InputError className="mt-2" message={errors.description} />
                </div>
                <div>
                    <InputLabel htmlFor="created_at" value="created_at" />
                    <TextInput
                        id="created_at"
                        name="created_at"
                        value={data.created_at}
                        className="mt-1 block w-full"
                        autoComplete="created_at"
                        isFocused={true}
                        onChange={(e) => setData('created_at', e.target.value)}
                        {...(type === 'success' ? { disabled: true } : {})}
                        rows={50}
                    />

                    <InputError className="mt-2" message={errors.created_at} />
                </div>
                {
                    type == 'edit' &&
                    <div>
                        <InputLabel htmlFor="body" value="body" />
                        <TextArea
                            id="body"
                            name="body"
                            value={data.body}
                            className="mt-1 block w-full"
                            autoComplete="body"
                            isFocused={true}
                            onChange={(e) => setData('body', e.target.value)}
                            disabled={true}
                            rows={50}
                            type='textarea '
                        />
                        <InputError className="mt-2" message={errors.body} />
                    </div>
                }


            </form>
        </section>
    );
}
