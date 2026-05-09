<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class ImageUploadService
{
    private static function getManager(): ?ImageManager
    {
        if (extension_loaded('imagick')) {
            return new ImageManager(new ImagickDriver());
        }
        if (extension_loaded('gd')) {
            return new ImageManager(new GdDriver());
        }
        return null;
    }

    /**
     * Upload et redimensionne une image
     */
    public static function upload(UploadedFile $file, string $folder, int $maxWidth = 1200, int $maxHeight = 1200): string
    {
        // Vérifier que c'est bien une image
        if (!getimagesize($file->getPathname())) {
            throw new \InvalidArgumentException('Le fichier n\'est pas une image valide');
        }

        $manager = self::getManager();
        
        // Si aucune extension image disponible, upload simple
        if (!$manager) {
            return $file->store($folder, 'public');
        }
        
        // Lire l'image
        $image = $manager->read($file->getPathname());

        // Redimensionner si trop grande
        $image->scaleDown(width: $maxWidth, height: $maxHeight);

        // Encoder en JPEG
        $encoded = $image->toJpeg(quality: 85);

        // Générer un nom unique
        $filename = uniqid() . '_' . time() . '.jpg';
        $path = $folder . '/' . $filename;

        // Sauvegarder
        Storage::disk('public')->put($path, $encoded->toString());

        return $path;
    }

    /**
     * Upload et redimensionne un avatar (carré)
     */
    public static function uploadAvatar(UploadedFile $file, int $size = 300): string
    {
        // Vérifier que c'est bien une image
        if (!getimagesize($file->getPathname())) {
            throw new \InvalidArgumentException('Le fichier n\'est pas une image valide');
        }

        $manager = self::getManager();
        
        // Si aucune extension image disponible, upload simple
        if (!$manager) {
            return $file->store('avatars', 'public');
        }

        $image = $manager->read($file->getPathname());

        // Redimensionner et crop en carré
        $image->cover($size, $size);

        // Encoder en JPEG
        $encoded = $image->toJpeg(quality: 90);

        $filename = 'avatar_' . uniqid() . '_' . time() . '.jpg';
        $path = 'avatars/' . $filename;

        Storage::disk('public')->put($path, $encoded->toString());

        return $path;
    }

    /**
     * Supprime une ancienne image
     */
    public static function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Génère une URL avec placeholder si l'image n'existe pas
     */
    public static function url(?string $path, string $type = 'default'): string
    {
        if (!$path) {
            return self::placeholder($type);
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        if (Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }

        return self::placeholder($type);
    }

    /**
     * URL de placeholder
     */
    public static function placeholder(string $type = 'default'): string
    {
        $placeholders = [
            'avatar' => 'https://ui-avatars.com/api/?name=User&background=3b82f6&color=fff&size=128',
            'post' => asset('images/placeholder-post.jpg'),
            'event' => asset('images/placeholder-event.jpg'),
            'default' => 'https://via.placeholder.com/400x300?text=Image+non+disponible',
        ];

        return $placeholders[$type] ?? $placeholders['default'];
    }
}
