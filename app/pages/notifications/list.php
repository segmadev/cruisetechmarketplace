<?php
require_once "pages/notifications/ini.php";
$n = new notifications;
$notifications = $n->get_pending_all_notification($userID, type: "sn.seen_time");
?>
<div class="offcanvas-header">
    <div class="offcanvas-title d-flex align-items-center justify-content-between" id="offcanvasExampleLabel">
        <h5 class="mb-0 fs-5 fw-semibold">Notifications</h5>
        <span class="badge bg-primary rounded-4 px-3 py-1 lh-sm"><?= $notifications->rowCount(); ?> unread</span>
    </div>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body">
    <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs d-flex" role="tablist">
            <li class="nav-item w-40">
                <a class="nav-link d-flex active" data-bs-toggle="tab" href="#notifications" role="tab">
                    <span><i class="ti ti-bell fs-4"></i>
                    </span>
                    <span class="ms-2 fs-2">Notifications</span>
                </a>
            </li>
            <li class="nav-item w-40">
                <a class="nav-link d-flex" data-bs-toggle="tab" href="#messages" role="tab">
                    <span><i class="ti ti-messages fs-4"></i>
                    </span>
                    <span class="ms-2 fs-2">Messages </span>
                </a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="notifications" role="tabpanel">
                <div class="p-3">
                    <?php if ($notifications->rowCount() > 0) {
                        foreach ($notifications as $row) {
                            if ($row['n_for'] == "message") {
                                if (!isset($messages_note)) {
                                    $messages_note = [];
                                }
                                $messages_note[] = $row;
                                continue;
                            }
                            $n->show_notification_list($row);
                        }
                    } ?>

                </div>
            </div>
            <div class="tab-pane p-3" id="messages" role="tabpanel">
                <?php if (isset($messages_note)) {
                    foreach ($messages_note as $row) {
                        $n->show_notification_list($row);
                    }
                } ?>
            </div>
        </div>
    </div>
</div>