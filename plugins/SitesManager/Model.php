<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\SitesManager;

use Piwik\Db;
use Piwik\Common;
use Exception;

class Model
{
    /**
     * Returns the list of websites from the ID array in parameters.
     *
     * @param array $idSites list of website ID
     * @param bool $limit
     * @param bool|int $offset
     * @param bool|String $filter
     * @return array
     */
    public function getSitesFromIds($idSites, $limit = false, $offset = false, $filter = false)
    {
        if (count($idSites) === 0) {
            return array();
        }

        $limitSqlString = '';
        if ($offset && $limit) {
            $limitSqlString = sprintf("LIMIT %d, %d", (int) $offset, (int) $limit);
        } elseif ($limit) {
            $limitSqlString = "LIMIT " . (int) $limit;
        }

        $idSites = array_map('intval', $idSites);

        $db = Db::get();

        $filterSql = "";

        if ($filter) {
            $filterJson = html_entity_decode($filter);
            $filterArray = json_decode($filterJson, true);

            foreach($filterArray as $name => $value) {
                if ($name=="sitesearch"){
                    if($value != ""){
                        $value = addslashes($value);
                        $filterSql .= " AND $name=$value";
                    }
                } else {
                    if (!empty($value)){
                        $value = addslashes($value);
                        $filterSql .= " AND $name LIKE '%".$value."%'";
                    }
                }
            }
        }


        $sites = $db->fetchAll("SELECT *
								FROM " . Common::prefixTable("site") . "
								WHERE idsite IN (" . implode(", ", $idSites) . ") $filterSql
								ORDER BY idsite ASC $limitSqlString");

        return $sites;
    }

/**
 * Returns the number of websites from the ID array in parameters.
 *
 * @param array $idSites list of website ID
 * @param bool $limit
 * @param bool|int $offset
 * @param bool|String $filter
 * @return number
 */
    public function getNumberOfSitesFromIds($idSites, $filter = false)
    {
        if (count($idSites) === 0) {
            return array();
        }

        $idSites = array_map('intval', $idSites);

        $db = Db::get();

        $filterSql = "";

        if ($filter) {
            $filterJson = html_entity_decode($filter);
            $filterArray = json_decode($filterJson, true);

            foreach($filterArray as $name => $value) {
                if ($name=="sitesearch"){
                    if($value != ""){
                        $value = addslashes($value);
                        $filterSql .= " AND $name=$value";
                    }
                } else {
                    if (!empty($value)){
                        $value = addslashes($value);
                        $filterSql .= " AND $name LIKE '%".$value."%'";
                    }
                }
            }
        }


        $numberOfSites = $db->fetchOne("SELECT COUNT(*)
								FROM " . Common::prefixTable("site") . "
								WHERE idsite IN (" . implode(", ", $idSites) . ") $filterSql");

        return $numberOfSites;
    }

    /**
     * Returns the website information : name, main_url
     *
     * @throws Exception if the site ID doesn't exist or the user doesn't have access to it
     * @param int $idSite
     * @return array
     */
    public function getSiteFromId($idSite)
    {
        $site = Db::get()->fetchRow("SELECT *
    								FROM " . Common::prefixTable("site") . "
    								WHERE idsite = ?", $idSite);

        return $site;
    }
}
