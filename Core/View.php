<?php

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;


class View
{
    private $file;

    private $data;

    private $twig;

    public function __construct($file, $data = null)
    {
        $this->file = $file;
        $this->data = $data;

        $twigLoader = new FilesystemLoader(INC_ROOT . '/Templates', '__main__');
        $this->twig = new Twig\Environment($twigLoader,
            [
                'cache' => INC_ROOT . '/Cache',
            ]);
        $this->twig->addGlobal('is_admin', IS_ADMIN);
    }
    public function __toString()
    {
        return $this->parseView();
    }

    public function parseView()
    {
        $file = $this->file.'.html.twig';

        try
        {
            if(is_null($this->data))
            {
                return $this->twig->render($file);
            }

            return $this->twig->render($file, $this->data);
        }

        catch(LoaderError $e)
        {
            return $this->getErrorMessage('loader', $e->getMessage());
        }

        catch(RuntimeError $e)
        {
            return $this->getErrorMessage('runtime', $e->getMessage());
        }

        catch(SyntaxError $e)
        {
            return $this->getErrorMessage('syntax', $e->getMessage());
        }
    }

    private function getErrorMessage($errorType, $errorMessage)
    {
        return sprintf("A %s error occured: %s", $errorType, $errorMessage);
    }
}
