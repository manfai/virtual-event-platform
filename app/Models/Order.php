<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OptimistDigital\NovaNotesField\Traits\HasNotes;
use Spatie\Activitylog\Traits\LogsActivity;

class Order extends Model
{
    use HasNotes, LogsActivity;
    
    protected static $logName = 'order';

    protected static $logAttributes = [ 
    'no',
    'address',
    'total_amount',
    'real_amount',
    'remark',
    'paid_at',
    'paid_no',
    'payment_status',
    'payment_method',
    'refund_status',
    'refund_no',
    'closed',
    'reviewed',
    'ship_status',
    'ship_data',
    'extra',
    ];

    const PAYMENT_STATUS_PENDING    = 'pending';
    const PAYMENT_STATUS_PROCESSING = 'processing';
    const PAYMENT_STATUS_SUCCESS    = 'success';
    const PAYMENT_STATUS_FAILED     = 'failed';

    const REFUND_STATUS_PENDING = 'pending';
    const REFUND_STATUS_APPLIED = 'applied';
    const REFUND_STATUS_PROCESSING = 'processing';
    const REFUND_STATUS_SUCCESS = 'success';
    const REFUND_STATUS_FAILED = 'failed';

    const SHIP_STATUS_PENDING = 'pending';
    const SHIP_STATUS_DELIVERED = 'delivered';
    const SHIP_STATUS_RECEIVED = 'received';

    public static $paymentStatusMap = [
        self::PAYMENT_STATUS_PENDING    => '提交中',
        self::PAYMENT_STATUS_PROCESSING => '處理中',
        self::PAYMENT_STATUS_SUCCESS    => '支付成功',
        self::PAYMENT_STATUS_FAILED     => '支付失敗',
    ];
    
    public static $refundStatusMap = [
        self::REFUND_STATUS_PENDING    => '未退款',
        self::REFUND_STATUS_APPLIED    => '已申請退款',
        self::REFUND_STATUS_PROCESSING => '退款中',
        self::REFUND_STATUS_SUCCESS    => '退款成功',
        self::REFUND_STATUS_FAILED     => '退款失敗',
    ];

    public static $shipStatusMap = [
        self::SHIP_STATUS_PENDING   => '未發貨',
        self::SHIP_STATUS_DELIVERED => '已發貨',
        self::SHIP_STATUS_RECEIVED  => '已收貨',
    ];

    protected $fillable = [
        'no',
        'address',
        'total_amount',
        'real_amount',
        'remark',
        'paid_at',
        'paid_no',
        'payment_status',
        'payment_method',
        'refund_status',
        'refund_no',
        'closed',
        'reviewed',
        'ship_status',
        'ship_data',
        'extra',
    ];

    protected $casts = [
        'closed'    => 'boolean',
        'reviewed'  => 'boolean',
        'address'   => 'json',
        'ship_data' => 'json',
        'extra'     => 'json',
    ];

    protected $dates = [
        'paid_at',
    ];

    protected static function boot()
    {
        parent::boot();
        // 监听模型创建事件，在写入数据库之前触发
        static::creating(function ($model) {
            // 如果模型的 no 字段为空
            if (!$model->no) {
                // 调用 findAvailableNo 生成订单流水号
                $model->no = static::findAvailableNo();
                // 如果生成失败，则终止创建订单
                if (!$model->no) {
                    return false;
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
   
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function paymentLog()
    {
        return $this->hasMany(OrderPayment::class)->orderBy('created_at','desc');
    }

    public static function findAvailableNo()
    {
        // 订单流水号前缀
        $prefix = date('YmdHis');
        for ($i = 0; $i < 10; $i++) {
            // 随机生成 6 位的数字
            $no = $prefix.str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            // 判断是否已经存在
            if (!static::query()->where('no', $no)->exists()) {
                return $no;
            }
        }
        \Log::warning('find order no failed');

        return false;
    }

    public function getFullAddressAttribute()
    {
        // return join(' ',$this->address);
        return $this->address['address'].', '.$this->address['zip'];
    }
    public function getContactNameAttribute()
    {
        return $this->address['contact_name'];
    }
    public function getContactPhoneAttribute()
    {
        return $this->address['contact_phone'];
    }

    public function couponCode()
    {
        return $this->belongsTo(CouponCode::class);
    }
}
