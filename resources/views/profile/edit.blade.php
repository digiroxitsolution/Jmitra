@extends('layouts.main')

@section('title', 'Role Create | Jmitra & Co. Pvt. Ltd')

@section('content')
<div class="container-fluid px-4">
    <div class="row g-3 my-2">
                <div class="d-grid d-flex justify-content-end">
                    <a class=""  href="{{ route('dashboard') }}">
                        <button class="btn add shadow" type="button" > {{ __('global.Back') }}</button>
                    </a>
                </div>
            </div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Profile Information Section -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <header class="mb-4">
                        <h3 class="h5 text-gray-900">{{ __('Profile Information') }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ __("Update your account's profile information and email address.") }}</p>
                    </header>
                    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                        @csrf
                        @method('patch')

                        <div class="form-group">
                            <label for="name">{{ __('Name') }}</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required autofocus>
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div class="form-group">
                            <label for="email">{{ __('Email') }}</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div>
                                    <p class="text-sm mt-2 text-gray-800">
                                        {{ __('Your email address is unverified.') }}

                                        <button form="send-verification" class="btn btn-link text-sm">
                                            {{ __('Click here to re-send the verification email.') }}
                                        </button>
                                    </p>

                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-2 font-medium text-sm text-green-600">
                                            {{ __('A new verification link has been sent to your email address.') }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                            @if (session('status') === 'profile-updated')
                                <p class="text-sm text-gray-600 mt-2">{{ __('Saved.') }}</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Update Password Section -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <header class="mb-4">
                        <h3 class="h5 text-gray-900">{{ __('Update Password') }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
                    </header>
                    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                        @csrf
                        @method('put')

                        <div class="form-group">
                            <label for="update_password_current_password">{{ __('Current Password') }}</label>
                            <input type="password" id="update_password_current_password" name="current_password" class="form-control" autocomplete="current-password">
                            <x-input-error class="mt-2" :messages="$errors->updatePassword->get('current_password')" />
                        </div>

                        <div class="form-group">
                            <label for="update_password_password">{{ __('New Password') }}</label>
                            <input type="password" id="update_password_password" name="password" class="form-control" autocomplete="new-password">
                            <x-input-error class="mt-2" :messages="$errors->updatePassword->get('password')" />
                        </div>

                        <div class="form-group">
                            <label for="update_password_password_confirmation">{{ __('Confirm Password') }}</label>
                            <input type="password" id="update_password_password_confirmation" name="password_confirmation" class="form-control" autocomplete="new-password">
                            <x-input-error class="mt-2" :messages="$errors->updatePassword->get('password_confirmation')" />
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                            @if (session('status') === 'password-updated')
                                <p class="text-sm text-gray-600 mt-2">{{ __('Saved.') }}</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Account Section -->
            <!-- <div class="card shadow mb-4">
                <div class="card-body">
                    <header class="mb-4">
                        <h3 class="h5 text-gray-900">{{ __('Delete Account') }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}</p>
                    </header>

                    <button class="btn btn-danger" data-toggle="modal" data-target="#confirmUserDeletionModal">{{ __('Delete Account') }}</button>

                    <!-- Modal for Account Deletion -->
                    <!-- <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" role="dialog" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmUserDeletionModalLabel">{{ __('Are you sure you want to delete your account?') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}</p>

                                    <form method="post" action="{{ route('profile.destroy') }}">
                                        @csrf
                                        @method('delete')

                                        <div class="form-group">
                                            <label for="password" class="sr-only">{{ __('Password') }}</label>
                                            <input type="password" id="password" name="password" class="form-control" placeholder="{{ __('Password') }}" required>
                                            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                                    <button type="submit" class="btn btn-danger">{{ __('Delete Account') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

        </div>
    </div>
</div>
@endsection
