<?php

namespace App\Core\FileSystem;


class FileSystem
{
    public function exists($path)
    {
        return file_exists($path);
    }


    public function get($path)
    {
        if ($this->isFile($path)) {
            return file_get_contents($path);
        }

        throw new \Exception("File does not exist at path {$path}");
    }


    public function getRequire($path)
    {
        if ($this->isFile($path)) {
            return require $path;
        }

        throw new \Exception("File does not exist at path {$path}");
    }


    public function requireOnce($file)
    {
        require_once $file;
    }


    public function hash($path)
    {
        return md5_file($path);
    }


    public function put($path, $contents, $lock = false)
    {
        return file_put_contents($path, $contents, $lock ? LOCK_EX : 0);
    }


    public function prepend($path, $data)
    {
        if ($this->exists($path)) {
            return $this->put($path, $data.$this->get($path));
        }

        return $this->put($path, $data);
    }


    public function append($path, $data)
    {
        return file_put_contents($path, $data, FILE_APPEND);
    }


    public function chmod($path, $mode = null)
    {
        if ($mode) {
            return chmod($path, $mode);
        }

        return substr(sprintf('%o', fileperms($path)), -4);
    }


    public function delete($paths)
    {
        $paths = is_array($paths) ? $paths : func_get_args();

        $success = true;

        foreach ($paths as $path) {
            try {
                if (!@unlink($path)) {
                    $success = false;
                }
            } catch (ErrorException $e) {
                $success = false;
            }
        }

        return $success;
    }


    public function move($path, $target)
    {
        return rename($path, $target);
    }


    public function copy($path, $target)
    {
        return copy($path, $target);
    }


    public function name($path)
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }


    public function basename($path)
    {
        return pathinfo($path, PATHINFO_BASENAME);
    }


    public function dirname($path)
    {
        return pathinfo($path, PATHINFO_DIRNAME);
    }


    public function extension($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }


    public function type($path)
    {
        return filetype($path);
    }


    public function mimeType($path)
    {
        return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
    }


    public function size($path)
    {
        return filesize($path);
    }


    public function lastModified($path)
    {
        return filemtime($path);
    }


    public function isDirectory($directory)
    {
        return is_dir($directory);
    }


    public function isReadable($path)
    {
        return is_readable($path);
    }


    public function isWritable($path)
    {
        return is_writable($path);
    }


    public function isFile($file)
    {
        return is_file($file);
    }


    public function glob($pattern, $flags = 0)
    {
        return glob($pattern, $flags);
    }


    public function files($directory)
    {
        //
    }


    public function directories($directory)
    {
        //
    }


    public function makeDirectory($path, $mode = 0755, $recursive = false, $force = false)
    {
        if ($force) {
            return @mkdir($path, $mode, $recursive);
        }

        return mkdir($path, $mode, $recursive);
    }
}