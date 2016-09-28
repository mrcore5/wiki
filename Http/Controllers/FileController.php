<?php namespace Mrcore\Wiki\Http\Controllers;

use View;
use Input;
use Route;
use Mrcore;
use Layout;
use Request;
use Response;
use Sabre\DAV;
use Mrcore\Wiki\Models\Post;
use Mreschke\Helpers\Guest;
use Mrcore\Wiki\Support\Sabredav;
use Mrcore\Wiki\Support\Filemanager;
use Mrcore\Wiki\Support\Filemanager\Url;

class FileController extends Controller
{

    /**
     * Route traffic based on HTTP verb and webdav vs HTTP protocols
     *
     * @return Response
     */
    public function fileRouter($slug = null)
    {
        // Instantiate our url analyzer class
        $url = new Url(Request::url());

        /*echo "<hr />";
        echo "Protocol: ".$url->getProtocol()."<br />";
        echo "Method: ".$url->getMethod()."<br />";
        echo "Url: ".$url->getUrl()."<br />";
        echo "Path: ".$url->getPath()."<br />";
        echo "AbsBase: ".$url->getAbsBase()."<br />";
        echo "Abs: ".$url->getAbs()."<br />";
        echo "Post ID: ".$url->getPostID()."<br />";
        echo "Static: ".$url->isStatic()."<br />";
        echo "Virtual: ".$url->isVirtual()."<br />";
        echo "Exists: ".$url->exists()."<br />";
        echo "Directory: ".$url->isDir()."<br />";
        echo "Readable: ".$url->isReadable()."<br />";
        echo "Writable: ".$url->isWritable()."<br />";
        echo "<hr />";
        exit();*/

        if ($url->isMethod('GET') || $url->isMethod('POST')) {
            // GET file or directory (webdav file only or http dir/file both)
            if ($url->getProtocol() == 'webdav') {
                return self::showSabredav($url);
            } else {
                if ($url->isDir()) {
                    return self::showDirectory($url);
                } else {
                    return self::showFile($url);
                }
            }
        } elseif ($url->isMethod('PROPFIND')) {
            // Display webdav only directory
            if ($url->isDir()) {
                return self::showMySabredav($url);
            }
        } elseif ($url->isMethod('PUT') || $url->isMethod('MKCOL')) {
            // Upload or make directory
            // $url will be that of the actual full path + file to make
            // but that file may not exist yet, so we need to get just the path
            // and see if thats writable
            $parentUrl = new Url($url->getUrlParent());
            if ($parentUrl->isWritable()) {
                return self::showSabredav($url);
            } else {
                return self::showError(403);
            }
        } elseif ($url->isMethod('DELETE')) {
            // Delete file or directory
            if ($url->exists()) {
                if ($url->isWritable()) {
                    return self::showSabredav($url);
                } else {
                    return self::showError(403);
                }
            } else {
                return self::showError(404);
            }
        } elseif ($url->isMethod('COPY')) {
            // Copy file from source to destination
            // Source needs read permissions while dest needs write
            if ($url->exists()) {
                if ($url->isReadable()) {
                    // Get destination url (full url) from 'Destination' http header
                    $dest = Request::header('Destination');
                    $segments = explode('/', $dest);
                    array_pop($segments);
                    $dest = implode($segments, '/');
                    $destUrl = new Url($dest);
                    if ($destUrl->isWritable()) {
                        return self::showSabredav($url);
                    } else {
                        return self::showError(403);
                    }
                } else {
                    return self::showError(403);
                }
            } else {
                return self::showError(404);
            }
        } elseif ($url->isMethod('MOVE')) {
            // Move file from source to destination
            // Both source and dest need write permissions
            if ($url->exists()) {
                if ($url->isWritable()) {
                    // Get destination url (full url) from 'Destination' http header
                    $dest = Request::header('Destination');
                    $pathinfo = pathinfo($dest);
                    $dest = $pathinfo['dirname'];
                    $destUrl = new Url($dest);
                    if ($destUrl->isWritable()) {
                        return self::showSabredav($url);
                    } else {
                        return self::showError(403);
                    }
                } else {
                    return self::showError(403);
                }
            } else {
                return self::showError(404);
            }
        }
    }

    /**
     * Show my custom sabredav protocol
     */
    public function showMySabredav($url)
    {
        $dir = new Sabredav\Directory($url->getPath(true), $url);
        $server = new DAV\Server($dir);
        $server->setBaseUri('/'.$url->getPath(true));
        $server->exec();
        return Response::make(null, http_response_code());
    }

    /**
     * Show default sabredav protocol
     */
    public function showSabredav($url)
    {
        // Use original DAV\FS\Directory to GET/PUT/DELETE/MKCOL
        $dir = new DAV\FS\Directory($url->getAbsBase());
        $server = new DAV\Server($dir);
        $server->setBaseUri('/');
        $server->exec();
        return Response::make(null, http_response_code());
    }

    /**
     * Download/Stream the file
     */
    public function showFile($url)
    {
        // Send 404 if not exist
        if (!$url->exists()) {
            return self::showError(404);
        }

        // Send 401 if access denied
        if (!$url->isReadable()) {
            return self::showError(401);
        }

        # File info
        $mimetype = $url->getMimeType();
        $filename = $url->getFilename();
        $abs = $url->getAbs();
        $size = $url->getSize();
        $extension = strtolower($url->getExtension());

        # Find any $_GET input variables
        $getWiki = Input::get('wiki');
        $getText = Input::get('text');
        $getMarkdown = Input::get('md');
        $getDownload = Input::get('download');

        if (isset($getText)) {
            $mimetype = 'text/plain';
        }
        if (($extension == 'wiki' && !isset($getText)) && !isset($getDownload) || isset($getWiki)) {
            // Parse wiki file content and display in simple mode!
            Layout::mode('simple');
            $post = Post::find($url->getPostID());
            Mrcore::post()->setModel($post);
            $content = $post->parse(file_get_contents($abs), 'wiki');
            return View::make('file.wiki', [
                'content' => $content
            ]);
        } elseif (($extension == 'md' && !isset($getText)) && !isset($getDownload) || isset($getMarkdown)) {
            // Parse markdown file content and display in simple mode!
            Layout::mode('simple');
            $post = Post::find($url->getPostID());
            Mrcore::post()->setModel($post);
            $content = $post->parse(file_get_contents($abs), 'markdown');
            return View::make('file.markdown', [
                'content' => $content
            ]);
        }

        if (isset($getDownload)) {
            //Force download file
            header("Content-type: application/octet-stream");
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            header("Cache-control: private");
        } else {
            // Inline Stream with cache
            header("Content-type: $mimetype");
            header('Content-Disposition: inline; filename="'.$filename.'"');

            // Checking if the client is validating his cache and if it is current.
            $expires = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? strtoupper($_SERVER['HTTP_IF_MODIFIED_SINCE']) : 'now'; //FRI, 22 MAY 2015 19:02:08 GMT
            $fileModified = strtoupper(gmdate('D, d M Y H:i:s', filemtime($abs)).' GMT');                                 //FRI, 22 MAY 2015 19:02:08 GMT
            if ($expires == $fileModified) {
                // Client's cache IS current, so we just respond '304 Not Modified'.
                header('HTTP/1.1 304 Not Modified');
                exit();
            } else {
                // Image not cached or cache outdated, we respond '200 OK' and output the image.
                header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($abs)).' GMT', true, 200);
            }
            #this control doesn't seem to matter
            #header("Cache-control: public"); //required for If-Modified-Since header to exist from browser
        }
        header("Content-length: $size");

        #AH! Finally, took forever on google to find this.
        #If you use this php downloader to download a file, you cannot browse my site
        #While the file is being downloaded, and you cannot obviously download another file at the same time
        #The whole site is frozen for that user/browser until the file is done.  If you open a different browser then the
        #site works.  This is because the session file is locked to prevent concurrent writes.  So opening a different browser
        #obviously gets a new session so the sites works again.  To solve this problem, all you have to do is call session_write_close()
        #which tricks the session into thinking the page is done, so it unlocks the session file allowing for further site browsing!!!
        #See http://stackoverflow.com/questions/1610168/downloading-files-with-php-only-downloading-one-at-a-time

        #Trick PHP into thinking this page is done, so it unlocks the session file to allow for further site navigation and downloading
        session_write_close();

        #Read the file into the stream
        readfile($abs);
        $response = Response::make(null, http_response_code());
        $response->header('Content-Type', $mimetype);
        return $response;
    }

    /**
     * Show directory contents, this is http only (not webdav)
     */
    public function showDirectory($url)
    {
        // Send 404 if not exist
        if (!$url->exists()) {
            return self::showError(404);
        }

        // Send 401 if access denied
        if (!$url->isReadable()) {
            return self::showError(401);
        }

        // Get our directory object (directory contents)
        // We use our sabredav dir object so I don't have to make two
        // We don't actually use webdav here but the object works great
        $dir = new Sabredav\Directory($url->getPath(), $url);

        // Default Parameters (name all so false is default)
        $params = array();
        $params['filter'] = null;
        $params['noheader'] = false;
        $params['nomenu'] = false;
        $params['nosubfolders'] = false;
        $params['nonav'] = false;
        $params['showhidden'] = false;
        $params['nocolumns'] = false;
        $params['noselection'] = false;
        $params['nobackground'] = false;
        $params['embed'] = false;
        $params['view'] = 'detail';

        // Update our default $params from input
        $postParams = Input::get('params');
        $tmp = explode(";", $postParams);
        foreach ($tmp as $param) {
            if (preg_match('"="', $param)) {
                $tmp2 = explode("=", $param);
                $key = $tmp2[0];
                $value = $tmp2[1];
            } else {
                $key = $param;
                $value = true;
            }
            if ($key) {
                $params[$key] = $value;
            }
        }

        // Show only the folder html content for ajax stream
        if (Request::ajax()) {
            #var_dump($params);

            $view = $params['view'];
            if ($view == 'detailpreview') {
                $view = 'detail';
            }

            # Temp Testing until I finish code
            #$view = 'detail';
            $params['nomenu'] = true;
            $params['nonav'] = true;

            return View::make('file.'.$view, array(
                'url' => $url,
                'dir' => $dir,
                'params' => $params
            ));


        // Show full layout and file manager
        } else {
            return View::make('file.show', array(
                'url' => $url,
                'dir' => $dir
            ));
        }
    }

    /**
     * Show error code
     */
    public function showError($responseCode)
    {
        // Get browser to detect if curl
        $browser = Guest::getBrowser();
        $isCurl = preg_match("/curl/i", $browser);

        if ($isCurl or Route::currentRouteName() == 'webdav') {
            // Return no body for clean curl output
            return Response::make(null, $responseCode);
        } else {
            // Show nice graphical 404 and 401 gui
            return Response::view('errors.'.$responseCode, array('view'=>$this), $responseCode);
        }
    }
}
