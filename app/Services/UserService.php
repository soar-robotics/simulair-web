<?php

namespace App\Services;

use App\Models\MongoDB\User\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class UserService
{
    protected $users;
    protected $storage;

    const PROFILE_IMAGES_PATH = 'users/profile_images';

    public function __construct(User $users)
    {
        $this->users = $users;
        $this->storage = Storage::disk('local');
    }

    private function findUser(string $userId) {
        return $this->users->find($userId);
    }

    public function getUser(string $userId)
    {
        $user = $this->findUser($userId);

        return $user;
    }

    public function update(string $userId, array $updateData)
    {
        $user = $this->findUser($userId);

        $user->fill($updateData);
        $user->save();

        return $user;
    }

    public function setProfileImage(string $userId, \Illuminate\Http\UploadedFile $imageFile)
    {
        $user = $this->findUser($userId);

        $extension = $imageFile->extension();
        $timestamp = Carbon::now()->format('YmdHis');
        $fileName = "{$timestamp}_{$user->id}.{$extension}";

        $storedPath = $this->storage->putFileAs(
            self::PROFILE_IMAGES_PATH, $imageFile, $fileName
        );

        $user->profile_image = $storedPath;
        $user->save();

        return $user;
    }

    public function getProfileImagePath(string $userId)
    {
        $user = $this->findUser($userId);

        if ($imagePath = $user->profile_image) {
            return $this->storage->path('/') . $imagePath;
        }

        return null;
    }
}
