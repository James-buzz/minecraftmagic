import React, { Fragment, useState } from 'react';
import { Dialog, Transition } from '@headlessui/react';
import PrimaryButton from '@/components/primary-button';

interface FeedbackDialogProps {
    isOpen: boolean;
    onClose: () => void;
    onSubmit: (feedback: { emoji: string; comment: string }) => void;
}

const EMOJI_OPTIONS = [
    { emoji: '😍', label: 'Love it' },
    { emoji: '🙂', label: 'Like it' },
    { emoji: '😐', label: 'Neutral' },
    { emoji: '😕', label: 'Dislike it' },
    { emoji: '😢', label: 'Hate it' }
];

const FeedbackDialog = ({ isOpen, onClose, onSubmit }: FeedbackDialogProps) => {
    const [selectedEmoji, setSelectedEmoji] = useState('');
    const [comment, setComment] = useState('');

    const handleSubmit = () => {
        if (selectedEmoji) {
            onSubmit({ emoji: selectedEmoji, comment });
            setSelectedEmoji('');
            setComment('');
            onClose();
        }
    };

    return (
        <Transition appear show={isOpen} as={Fragment}>
            <Dialog as="div" className="relative z-50" onClose={onClose}>
                <Transition.Child
                    as={Fragment}
                    enter="ease-out duration-300"
                    enterFrom="opacity-0"
                    enterTo="opacity-100"
                    leave="ease-in duration-200"
                    leaveFrom="opacity-100"
                    leaveTo="opacity-0"
                >
                    <div className="fixed inset-0 bg-black bg-opacity-75" />
                </Transition.Child>

                <div className="fixed inset-0 overflow-y-auto">
                    <div className="flex min-h-full items-center justify-center p-4">
                        <Transition.Child
                            as={Fragment}
                            enter="ease-out duration-300"
                            enterFrom="opacity-0 scale-95"
                            enterTo="opacity-100 scale-100"
                            leave="ease-in duration-200"
                            leaveFrom="opacity-100 scale-100"
                            leaveTo="opacity-0 scale-95"
                        >
                            <Dialog.Panel className="w-full max-w-md transform overflow-hidden rounded-lg bg-gray-800 p-6 text-left align-middle shadow-xl transition-all">
                                <Dialog.Title className="text-lg font-bold leading-6 text-white">
                                    Share Your Feedback
                                </Dialog.Title>

                                <div className="mt-4">
                                    <div className="flex justify-center space-x-4 py-4">
                                        {EMOJI_OPTIONS.map(({ emoji, label }) => (
                                            <button
                                                key={emoji}
                                                onClick={() => setSelectedEmoji(emoji)}
                                                className={`rounded-full p-2 text-4xl transition-transform hover:scale-110 ${
                                                    selectedEmoji === emoji ? 'bg-gray-700 ring-2 ring-indigo-500' : ''
                                                }`}
                                                title={label}
                                            >
                                                {emoji}
                                            </button>
                                        ))}
                                    </div>

                                    <textarea
                                        placeholder="Add a comment (optional)"
                                        value={comment}
                                        onChange={(e) => setComment(e.target.value)}
                                        className="mt-4 w-full rounded-lg bg-gray-700 p-2 text-white placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        rows={3}
                                    />

                                    <div className="mt-6 flex justify-end space-x-3">
                                        <button
                                            type="button"
                                            className="rounded-md bg-gray-700 px-4 py-2 text-sm text-white hover:bg-gray-600"
                                            onClick={onClose}
                                        >
                                            Cancel
                                        </button>
                                        <PrimaryButton
                                            onClick={handleSubmit}
                                            disabled={!selectedEmoji}
                                            className="px-4 py-2"
                                        >
                                            Submit
                                        </PrimaryButton>
                                    </div>
                                </div>
                            </Dialog.Panel>
                        </Transition.Child>
                    </div>
                </div>
            </Dialog>
        </Transition>
    );
};

export default FeedbackDialog;
