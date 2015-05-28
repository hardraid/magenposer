<?php

namespace Magenposer\Composer;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class Installer extends LibraryInstaller
{
    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        return 'app/code/community';
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        return 'magenposer-module' === $packageType;
    }

    /**
     * {@inheritDoc}
     */
    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        parent::install($repo, $package);
        $extra = $package->getExtra();
        if (isset($extra['module'])) {
            $data = array('modules' => array(
                        $extra['module'] => array('active' => 'true',
                                                  'codePool' => 'community')));
            $xml = new SimpleXMLElement('<config/>');
            array_walk_recursive($data, array($xml, 'addChild'));
            file_put_contents('app/etc/modules/' . $extra['module'] . '.xml', $xml->asXML());
        }
    }
}
