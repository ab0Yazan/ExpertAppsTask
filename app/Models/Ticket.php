<?php

namespace App\Models;

use App\Enums\TicketStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ticket extends Model
{
    /** @use HasFactory<\Database\Factories\TicketFactory> */
    use HasFactory;

    protected $fillable = [
        "user_id",
        "name",
        "status",
        "description"
    ];

    protected $with = ['categories'];

    protected $casts = [
        "status" => TicketStatusEnum::class,
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function persist()
    {
        DB::transaction(function ()  {
            $this->save();
            $this->categories()->sync($this->categories->pluck("id")->toArray());
        });

        return $this;
    }
}
