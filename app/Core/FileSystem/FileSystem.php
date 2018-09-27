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

        throw new FileNotFoundException("File does not exist at path {$path}");
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

    /**
     * Get an array of all files in a directory.
     *
     * @param  string  $directory
     * @param  bool  $hidden
     * @return \Symfony\Component\Finder\SplFileInfo[]
     */
    public function files($directory, $hidden = false)
    {
        return iterator_to_array(
            Finder::create()->files()->ignoreDotFiles(! $hidden)->in($directory)->depth(0)->sortByName(),
            false
        );
    }

    /**
     * Get all of the files from the given directory (recursive).
     *
     * @param  string  $directory
     * @param  bool  $hidden
     * @return \Symfony\Component\Finder\SplFileInfo[]
     */
    public function allFiles($directory, $hidden = false)
    {
        return iterator_to_array(
            Finder::create()->files()->ignoreDotFiles(! $hidden)->in($directory)->sortByName(),
            false
        );
    }

    /**
     * Get all of the directories within a given directory.
     *
     * @param  string  $directory
     * @return array
     */
    public function directories($directory)
    {
        $directories = [];

        foreach (Finder::create()->in($directory)->directories()->depth(0)->sortByName() as $dir) {
            $directories[] = $dir->getPathname();
        }

        return $directories;
    }

    /**
     * Create a directory.
     *
     * @param  string  $path
     * @param  int     $mode
     * @param  bool    $recursive
     * @param  bool    $force
     * @return bool
     */
    public function makeDirectory($path, $mode = 0755, $recursive = false, $force = false)
    {
        if ($force) {
            return @mkdir($path, $mode, $recursive);
        }

        return mkdir($path, $mode, $recursive);
    }

    /**
     * Move a directory.
     *
     * @param  string  $from
     * @param  string  $to
     * @param  bool  $overwrite
     * @return bool
     */
    public function moveDirectory($from, $to, $overwrite = false)
    {
        if ($overwrite && $this->isDirectory($to)) {
            if (! $this->deleteDirectory($to)) {
                return false;
            }
        }

        return @rename($from, $to) === true;
    }

    /**
     * Copy a directory from one location to another.
     *
     * @param  string  $directory
     * @param  string  $destination
     * @param  int     $options
     * @return bool
     */
    public function copyDirectory($directory, $destination, $options = null)
    {
        if (! $this->isDirectory($directory)) {
            return false;
        }

        $options = $options ?: FilesystemIterator::SKIP_DOTS;

        // If the destination directory does not actually exist, we will go ahead and
        // create it recursively, which just gets the destination prepared to copy
        // the files over. Once we make the directory we'll proceed the copying.
        if (! $this->isDirectory($destination)) {
            $this->makeDirectory($destination, 0777, true);
        }

        $items = new FilesystemIterator($directory, $options);

        foreach ($items as $item) {
            // As we spin through items, we will check to see if the current file is actually
            // a directory or a file. When it is actually a directory we will need to call
            // back into this function recursively to keep copying these nested folders.
            $target = $destination.'/'.$item->getBasename();

            if ($item->isDir()) {
                $path = $item->getPathname();

                if (! $this->copyDirectory($path, $target, $options)) {
                    return false;
                }
            }

            // If the current items is just a regular file, we will just copy this to the new
            // location and keep looping. If for some reason the copy fails we'll bail out
            // and return false, so the developer is aware that the copy process failed.
            else {
                if (! $this->copy($item->getPathname(), $target)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Recursively delete a directory.
     *
     * The directory itself may be optionally preserved.
     *
     * @param  string  $directory
     * @param  bool    $preserve
     * @return bool
     */
    public function deleteDirectory($directory, $preserve = false)
    {
        if (! $this->isDirectory($directory)) {
            return false;
        }

        $items = new FilesystemIterator($directory);

        foreach ($items as $item) {
            // If the item is a directory, we can just recurse into the function and
            // delete that sub-directory otherwise we'll just delete the file and
            // keep iterating through each file until the directory is cleaned.
            if ($item->isDir() && ! $item->isLink()) {
                $this->deleteDirectory($item->getPathname());
            }

            // If the item is just a file, we can go ahead and delete it since we're
            // just looping through and waxing all of the files in this directory
            // and calling directories recursively, so we delete the real path.
            else {
                $this->delete($item->getPathname());
            }
        }

        if (! $preserve) {
            @rmdir($directory);
        }

        return true;
    }

    /**
     * Remove all of the directories within a given directory.
     *
     * @param  string  $directory
     * @return bool
     */
    public function deleteDirectories($directory)
    {
        $allDirectories = $this->directories($directory);

        if (! empty($allDirectories)) {
            foreach ($allDirectories as $directoryName) {
                $this->deleteDirectory($directoryName);
            }

            return true;
        }

        return false;
    }

    /**
     * Empty the specified directory of all files and folders.
     *
     * @param  string  $directory
     * @return bool
     */
    public function cleanDirectory($directory)
    {
        return $this->deleteDirectory($directory, true);
    }

    private function normalizeDir($dir)
    {
        return rtrim($dir, '/' . \DIRECTORY_SEPARATOR);
    }
}