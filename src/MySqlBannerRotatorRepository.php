<?php

require_once('RotatorRepository.php');

class MySqlBannerRotatorRepository extends RotatorRepository
{
    protected $mysqli = null;
    protected $cachePrefix = 'b_';

    protected function assembleFromArray($rotator)
    {
        $banners = [];
        foreach ($rotator['promo'] as $banner) {
            $banners[] = new Banner(
                $banner['id'],
                $banner['weight'],
                $banner['quality'],
                $banner['size'],
                $banner['image']
            );
        }

        return new Rotator(
            $rotator['id'],
            $rotator['user_id'],
            $rotator['sub_id'],
            $rotator['url'],
            $banners
        );
    }

    protected function rotatorOfId($rotatorId)
    {
        $this->connect();

        $query = "SELECT url, subId, userId FROM banner_rotator WHERE rotatorId='{$rotatorId}'";
        $res = mysqli_query($this->mysqli, $query);

        $rotator = null;

        if ($row = mysqli_fetch_assoc($res)) {
            $rotator = [
                'id' => $rotatorId,
                'url' => $row['url'],
                'sub_id' => $row['subId'],
                'user_id' => $row['userId'],
            ];

            $query = "SELECT brs.weight, brp.id, brp.image, brp.size, brp.quality
                FROM banner_rotator_stat brs
                JOIN banner_rotator_promo brp ON brp.id=brs.banner_id
                WHERE brs.weight>0 AND brs.rotator_id='{$rotatorId}'";
            $res = mysqli_query($this->mysqli, $query);

            while ($row = mysqli_fetch_assoc($res)) {
                $rotator['promo'][] = $row;
            }
        }

        return $rotator;
    }

    protected function connect()
    {
        if ($this->mysqli) {
            return;
        }

        $this->mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if (!$this->mysqli) {
            throw new Exception('Cannot connect to MySQL database');
        }
    }
}
