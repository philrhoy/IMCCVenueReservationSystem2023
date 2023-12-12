<?php 
    
    class NotificationHelper{
        private $CREATE_RESERVATION_MSG = "%s created a new reservation [%s]. Please review.";
        private $UPDATE_RESERVATION_MSG = "%s updated reservation [%s]. Please review the updates.";

        private $APPROVE_RESERVATION_MSG = "Reservation [%s] was Approved by Admin %s.";
        private $REJECT_RESERVATION_MSG = "Reservation [%s] was Rejected by Admin %s. Please review and update the reservation.";
        
        public function __construct(){ /*echo "debug class constructor";*/ }

        public function createNotification($RESERVATION_ID = "", $USER_NAME = "", $ACTION = "CREATE"){
            $RETURN_NOTIFICATION = "";

            switch($ACTION){
                case "CREATE" :
                    $RETURN_NOTIFICATION = sprintf($this->CREATE_RESERVATION_MSG, $USER_NAME, $RESERVATION_ID);
                    break;
                case "UPDATE" :
                    $RETURN_NOTIFICATION = sprintf($this->UPDATE_RESERVATION_MSG, $USER_NAME, $RESERVATION_ID);
                    break;
                case "APPROVE" :
                    $RETURN_NOTIFICATION = sprintf($this->APPROVE_RESERVATION_MSG, $RESERVATION_ID, $USER_NAME);
                    break;
                case "REJECT" :
                    $RETURN_NOTIFICATION = sprintf($this->REJECT_RESERVATION_MSG, $RESERVATION_ID, $USER_NAME);
                    break;
                default:
                    $RETURN_NOTIFICATION = "TEST DEBUG";
            }

            return $RETURN_NOTIFICATION;
        }
    }

?>
