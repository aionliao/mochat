<?php

declare(strict_types=1);
/**
 * This file is part of MoChat.
 * @link     https://mo.chat
 * @document https://mochat.wiki
 * @contact  group@mo.chat
 * @license  https://github.com/mochat-cloud/mochat/blob/master/LICENSE
 */
namespace MoChat\Plugin\RoomSop\Action\Sidebar;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use MoChat\App\WorkRoom\Contract\WorkRoomContract;
use MoChat\Framework\Action\AbstractAction;
use MoChat\Plugin\RoomSop\Logic\TipSopAddRoomLogic;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\Middleware;
use MoChat\App\Common\Middleware\SidebarAuthMiddleware;

/**
 * h5侧边栏接口.
 * @Controller
 */
class TipSopAddRoom extends AbstractAction
{
    /**
     * @var TipSopAddRoomLogic
     */
    protected $tipSopAddRoom;

    /**
     * @Inject
     * @var WorkRoomContract
     */
    protected $workRoomService;

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    public function __construct(TipSopAddRoomLogic $tipSopAddRoom, RequestInterface $request)
    {
        $this->tipSopAddRoom = $tipSopAddRoom;
        $this->request       = $request;
    }

    /**
     * @Middlewares({
     *     @Middleware(SidebarAuthMiddleware::class)
     * })
     * @RequestMapping(path="/sidebar/roomSop/tipSopAddRoom", methods="get")
     */
    public function handle(): array
    {
        $params['corpId'] = (int) $this->request->input('corpId');  //企业 id
        $params['id']     = (int) $this->request->input('id');               //sop id
        $roomId           = $this->request->input('roomId');                      //群聊id
        $params['roomId'] = $this->workRoomService->getWorkRoomsByCorpIdWxChatId($params['corpId'], $roomId, ['id'])['id'];       //群聊id

        return $this->tipSopAddRoom->handle($params);
    }
}
