<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RS_Vendor\Twig\Loader;

use RS_Vendor\Twig\Error\LoaderError;
use RS_Vendor\Twig\Source;
/**
 * Loads template from the filesystem.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class FilesystemLoader implements \RS_Vendor\Twig\Loader\LoaderInterface, \RS_Vendor\Twig\Loader\ExistsLoaderInterface, \RS_Vendor\Twig\Loader\SourceContextLoaderInterface
{
    /** Identifier of the main namespace. */
    const MAIN_NAMESPACE = '__main__';
    protected $paths = [];
    protected $cache = [];
    protected $errorCache = [];
    private $rootPath;
    /**
     * @param string|array $paths    A path or an array of paths where to look for templates
     * @param string|null  $rootPath The root path common to all relative paths (null for getcwd())
     */
    public function __construct($paths = [], string $rootPath = null)
    {
        $this->rootPath = (null === $rootPath ? \getcwd() : $rootPath) . \DIRECTORY_SEPARATOR;
        if (\false !== ($realPath = \realpath($rootPath))) {
            $this->rootPath = $realPath . \DIRECTORY_SEPARATOR;
        }
        if ($paths) {
            $this->setPaths($paths);
        }
    }
    /**
     * Returns the paths to the templates.
     *
     * @param string $namespace A path namespace
     *
     * @return array The array of paths where to look for templates
     */
    public function getPaths($namespace = self::MAIN_NAMESPACE)
    {
        return isset($this->paths[$namespace]) ? $this->paths[$namespace] : [];
    }
    /**
     * Returns the path namespaces.
     *
     * The main namespace is always defined.
     *
     * @return array The array of defined namespaces
     */
    public function getNamespaces()
    {
        return \array_keys($this->paths);
    }
    /**
     * Sets the paths where templates are stored.
     *
     * @param string|array $paths     A path or an array of paths where to look for templates
     * @param string       $namespace A path namespace
     */
    public function setPaths($paths, $namespace = self::MAIN_NAMESPACE)
    {
        if (!\is_array($paths)) {
            $paths = [$paths];
        }
        $this->paths[$namespace] = [];
        foreach ($paths as $path) {
            $this->addPath($path, $namespace);
        }
    }
    /**
     * Adds a path where templates are stored.
     *
     * @param string $path      A path where to look for templates
     * @param string $namespace A path namespace
     *
     * @throws LoaderError
     */
    public function addPath($path, $namespace = self::MAIN_NAMESPACE)
    {
        // invalidate the cache
        $this->cache = $this->errorCache = [];
        $checkPath = $this->isAbsolutePath($path) ? $path : $this->rootPath . $path;
        if (!\is_dir($checkPath)) {
            throw new \RS_Vendor\Twig\Error\LoaderError(\sprintf('The "%s" directory does not exist ("%s").', $path, $checkPath));
        }
        $this->paths[$namespace][] = \rtrim($path, '/\\');
    }
    /**
     * Prepends a path where templates are stored.
     *
     * @param string $path      A path where to look for templates
     * @param string $namespace A path namespace
     *
     * @throws LoaderError
     */
    public function prependPath($path, $namespace = self::MAIN_NAMESPACE)
    {
        // invalidate the cache
        $this->cache = $this->errorCache = [];
        $checkPath = $this->isAbsolutePath($path) ? $path : $this->rootPath . $path;
        if (!\is_dir($checkPath)) {
            throw new \RS_Vendor\Twig\Error\LoaderError(\sprintf('The "%s" directory does not exist ("%s").', $path, $checkPath));
        }
        $path = \rtrim($path, '/\\');
        if (!isset($this->paths[$namespace])) {
            $this->paths[$namespace][] = $path;
        } else {
            \array_unshift($this->paths[$namespace], $path);
        }
    }
    public function getSourceContext($name)
    {
        if (null === ($path = $this->findTemplate($name)) || \false === $path) {
            return new \RS_Vendor\Twig\Source('', $name, '');
        }
        return new \RS_Vendor\Twig\Source(\file_get_contents($path), $name, $path);
    }
    public function getCacheKey($name)
    {
        if (null === ($path = $this->findTemplate($name)) || \false === $path) {
            return '';
        }
        $len = \strlen($this->rootPath);
        if (0 === \strncmp($this->rootPath, $path, $len)) {
            return \substr($path, $len);
        }
        return $path;
    }
    public function exists($name)
    {
        $name = $this->normalizeName($name);
        if (isset($this->cache[$name])) {
            return \true;
        }
        return null !== ($path = $this->findTemplate($name, \false)) && \false !== $path;
    }
    public function isFresh($name, $time)
    {
        // false support to be removed in 3.0
        if (null === ($path = $this->findTemplate($name)) || \false === $path) {
            return \false;
        }
        return \filemtime($path) < $time;
    }
    /**
     * Checks if the template can be found.
     *
     * In Twig 3.0, findTemplate must return a string or null (returning false won't work anymore).
     *
     * @param string $name  The template name
     * @param bool   $throw Whether to throw an exception when an error occurs
     *
     * @return string|false|null The template name or false/null
     */
    protected function findTemplate($name, $throw = \true)
    {
        $name = $this->normalizeName($name);
        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }
        if (isset($this->errorCache[$name])) {
            if (!$throw) {
                return \false;
            }
            throw new \RS_Vendor\Twig\Error\LoaderError($this->errorCache[$name]);
        }
        try {
            $this->validateName($name);
            list($namespace, $shortname) = $this->parseName($name);
        } catch (\RS_Vendor\Twig\Error\LoaderError $e) {
            if (!$throw) {
                return \false;
            }
            throw $e;
        }
        if (!isset($this->paths[$namespace])) {
            $this->errorCache[$name] = \sprintf('There are no registered paths for namespace "%s".', $namespace);
            if (!$throw) {
                return \false;
            }
            throw new \RS_Vendor\Twig\Error\LoaderError($this->errorCache[$name]);
        }
        foreach ($this->paths[$namespace] as $path) {
            if (!$this->isAbsolutePath($path)) {
                $path = $this->rootPath . $path;
            }
            if (\is_file($path . '/' . $shortname)) {
                if (\false !== ($realpath = \realpath($path . '/' . $shortname))) {
                    return $this->cache[$name] = $realpath;
                }
                return $this->cache[$name] = $path . '/' . $shortname;
            }
        }
        $this->errorCache[$name] = \sprintf('Unable to find template "%s" (looked into: %s).', $name, \implode(', ', $this->paths[$namespace]));
        if (!$throw) {
            return \false;
        }
        throw new \RS_Vendor\Twig\Error\LoaderError($this->errorCache[$name]);
    }
    private function normalizeName($name)
    {
        return \preg_replace('#/{2,}#', '/', \str_replace('\\', '/', $name));
    }
    private function parseName($name, $default = self::MAIN_NAMESPACE)
    {
        if (isset($name[0]) && '@' == $name[0]) {
            if (\false === ($pos = \strpos($name, '/'))) {
                throw new \RS_Vendor\Twig\Error\LoaderError(\sprintf('Malformed namespaced template name "%s" (expecting "@namespace/template_name").', $name));
            }
            $namespace = \substr($name, 1, $pos - 1);
            $shortname = \substr($name, $pos + 1);
            return [$namespace, $shortname];
        }
        return [$default, $name];
    }
    private function validateName($name)
    {
        if (\false !== \strpos($name, "\0")) {
            throw new \RS_Vendor\Twig\Error\LoaderError('A template name cannot contain NUL bytes.');
        }
        $name = \ltrim($name, '/');
        $parts = \explode('/', $name);
        $level = 0;
        foreach ($parts as $part) {
            if ('..' === $part) {
                --$level;
            } elseif ('.' !== $part) {
                ++$level;
            }
            if ($level < 0) {
                throw new \RS_Vendor\Twig\Error\LoaderError(\sprintf('Looks like you try to load a template outside configured directories (%s).', $name));
            }
        }
    }
    private function isAbsolutePath($file)
    {
        return \strspn($file, '/\\', 0, 1) || \strlen($file) > 3 && \ctype_alpha($file[0]) && ':' === $file[1] && \strspn($file, '/\\', 2, 1) || null !== \parse_url($file, \PHP_URL_SCHEME);
    }
}
\class_alias('RS_Vendor\\Twig\\Loader\\FilesystemLoader', 'RS_Vendor\\Twig_Loader_Filesystem');
