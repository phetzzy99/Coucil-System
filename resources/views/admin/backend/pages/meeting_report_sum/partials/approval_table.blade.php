<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th style="width: 20%">ผู้รับรอง</th>
                <th style="width: 15%">สถานะ</th>
                <th style="width: 45%">ความคิดเห็น</th>
                <th style="width: 20%">วันที่รับรอง</th>
            </tr>
        </thead>
        <tbody>
            @foreach($approvals as $approval)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar avatar-xs me-2">
                                    <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                        {{ substr($approval['user']->first_name, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                {{ $approval['user']->prefix_name }} {{ $approval['user']->first_name }} {{ $approval['user']->last_name }}
                                <div class="small text-muted">
                                    {{ $approval['user']->position->name ?? '' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($approval['type'] == 'no_changes')
                            <span class="badge bg-success">รับรองโดยไม่มีแก้ไขss</span>
                        @else
                            <span class="badge bg-warning">รับรองโดยมีแก้ไข</span>
                        @endif
                    </td>
                    <td>
                        @if($approval['comments'])
                            <div class="comment-text">
                                {{ $approval['comments'] }}
                            </div>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <div class="small text-muted">
                            {{ date('d/m/Y H:i', strtotime($approval['created_at'] ?? now())) }}
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
