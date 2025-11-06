 <?php
 use App\Models\Order;

 it('creates non-vip order when capacity allows', function () {
    // Ensure no actives
    Order::query()->delete();
    $payload = [
        'items' => ['burger','fries'],
        'pickup_time' => now()->addHour()->toIso8601String(),
    ];
    $res = $this->postJson('/api/orders', $payload)->assertCreated();
    $res->assertJsonStructure(['data' => ['id','items','pickup_time','status']]);
 });
 
 it('blocks non-vip when full and suggests next pickup', function () {
    config(['app.kitchen_capacity' => 1]);
    Order::factory()->create(); // 1 active already
    $payload = [
        'items' => ['pizza'],
        'pickup_time' => now()->addHour()->toIso8601String(),
    ];
    $this->postJson('/api/orders', $payload)
    ->assertStatus(429)
    ->assertJsonStructure(['message','suggested_next_pickup_time']);
});

 it('allows VIP even when full', function () {
    config(['app.kitchen_capacity' => 0]);
    $payload = [
        'items' => ['taco'],
        'pickup_time' => now()->addHour()->toIso8601String(),
        'VIP' => true,
    ];
    $this->postJson('/api/orders', $payload)->assertCreated();
 });

 it('lists active orders', function () {
    Order::factory()->count(2)->create();
    $this->getJson('/api/orders/active')->assertOk()->assertJsonStructure(['data' => [['id','items']]]);
 });
 it('completes an order idempotently', function () {
    $o = Order::factory()->create();
    $this->postJson("/api/orders/{$o->id}/complete")->assertOk()->assertJsonPath('data.status','completed');
    // doing it again keeps completed
    $this->postJson("/api/orders/{$o->id}/complete")->assertOk()->assertJsonPath('data.status','completed');
 });