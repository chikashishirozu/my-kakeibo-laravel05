@extends('layouts.app')

@section('title', 'プロファイル編集')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">👤 プロファイル編集</h4>
                </div>
                <div class="card-body">
                    <!-- プロファイル情報更新 -->
                    <div class="mb-4">
                        <h5>プロファイル情報</h5>
                        <form method="post" action="{{ route('profile.update') }}">
                            @csrf
                            @method('patch')

                            <div class="mb-3">
                                <label for="name" class="form-label">名前</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">メールアドレス</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                    <div class="mt-2">
                                        <p class="text-warning">
                                            メールアドレスが未確認です。
                                            <button form="send-verification" class="btn btn-link p-0">
                                                確認メールを再送信
                                            </button>
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <button type="submit" class="btn btn-primary">保存</button>
                                
                                @if (session('status') === 'profile-updated')
                                    <p class="text-success mb-0">保存されました。</p>
                                @endif
                            </div>
                        </form>
                    </div>

                    <hr>

                    <!-- パスワード変更 -->
                    <div class="mb-4">
                        <h5>パスワード変更</h5>
                        <form method="post" action="{{ route('password.update') }}">
                            @csrf
                            @method('put')

                            <div class="mb-3">
                                <label for="current_password" class="form-label">現在のパスワード</label>
                                <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                                       id="current_password" name="current_password">
                                @error('current_password', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">新しいパスワード</label>
                                <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                                       id="password" name="password">
                                @error('password', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">パスワード確認</label>
                                <input type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                                       id="password_confirmation" name="password_confirmation">
                                @error('password_confirmation', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <button type="submit" class="btn btn-primary">パスワード更新</button>
                                
                                @if (session('status') === 'password-updated')
                                    <p class="text-success mb-0">パスワードが更新されました。</p>
                                @endif
                            </div>
                        </form>
                    </div>

                    <hr>

                    <!-- アカウント削除 -->
                    <div class="mb-4">
                        <h5 class="text-danger">アカウント削除</h5>
                        <p class="text-muted">
                            アカウントを削除すると、すべてのデータが永続的に削除されます。この操作は取り消すことができません。
                        </p>
                        
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            アカウントを削除
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- アカウント削除確認モーダル -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">アカウント削除の確認</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                
                <div class="modal-body">
                    <p>本当にアカウントを削除しますか？</p>
                    <p class="text-danger">この操作は取り消すことができません。</p>
                    
                    <div class="mb-3">
                        <label for="password_delete" class="form-label">パスワードを入力して確認してください</label>
                        <input type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                               id="password_delete" name="password" placeholder="パスワード">
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                    <button type="submit" class="btn btn-danger">削除する</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- メール確認再送信フォーム -->
@if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
    <form id="send-verification" method="post" action="{{ route('verification.send') }}" style="display: none;">
        @csrf
    </form>
@endif
@endsection
