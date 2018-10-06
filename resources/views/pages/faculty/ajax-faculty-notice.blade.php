<div class="panel panel-info">
 <p style="margin-left:0; font-weight: 400; font-size: 12px; line-height: 12px; color: #5858ED; font-family: Georgia,serif;">
  <i><strong>Date : </strong>{{$notice_view->created_at}}<br>
  <strong>From : </strong>{{$notice_view->notice_from_type}}</i>
 </p><br>
 <div class="panel-heading"><strong>Subject:</strong> {{$notice_view->notice_subject}}</div>
 <div class="panel-body">
  <p style="margin-top: 0;  font-weight: 400; font-size: 14px; line-height: 22px; color: #050505; font-family: Georgia,serif; margin-bottom: 22px;"> <strong>Message:</strong> {{$notice_view->notice_message}}</p>
 </div>
</div>