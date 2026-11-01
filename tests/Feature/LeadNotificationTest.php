<?php

namespace Tests\Feature;

use App\Mail\LeadReceived;
use App\Models\ContactMessage;
use App\Models\DemoRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class LeadNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['site.leads_to' => ['sales@nextbook.af', 'owner@example.com']]);
        Mail::fake();
    }

    public function test_a_demo_request_emails_the_team_with_the_details(): void
    {
        $this->post('/fa/demo', [
            'name' => 'Ahmad', 'phone' => '0700000000', 'email' => 'ahmad@example.com',
            'company' => 'Noor Pharmacy', 'business_type' => 'pharmacy', 'branches' => 2,
            'message' => 'پکیج «حرفه‌ای» را می‌خواهم.',
        ])->assertRedirect();

        Mail::assertSent(LeadReceived::class, function (LeadReceived $mail) {
            $this->assertTrue($mail->hasTo('sales@nextbook.af'));
            $this->assertTrue($mail->hasTo('owner@example.com'));
            // Replying goes to the visitor.
            $this->assertTrue($mail->hasReplyTo('ahmad@example.com'));
            $this->assertStringContainsString('Ahmad', $mail->envelope()->subject);

            $html = $mail->render();
            $this->assertStringContainsString('0700000000', $html);
            $this->assertStringContainsString('Noor Pharmacy', $html);
            $this->assertStringContainsString('دواخانه', $html);
            $this->assertStringContainsString('پکیج «حرفه‌ای» را می‌خواهم.', $html);
            $this->assertStringContainsString('/admin/demo-requests', $html);

            return true;
        });
    }

    public function test_a_contact_message_emails_the_team_too(): void
    {
        $this->post('/en/contact', ['name' => 'Ali', 'phone' => '0799999999', 'message' => 'Hello there'])->assertRedirect();

        Mail::assertSent(LeadReceived::class, fn (LeadReceived $mail) => $mail->lead instanceof ContactMessage
            && str_contains($mail->render(), 'Hello there')
            && $mail->replyTo === []);          // no email address given, so no reply-to
    }

    public function test_a_bot_that_fills_the_honeypot_triggers_no_email(): void
    {
        $this->post('/en/demo', ['name' => 'Bot', 'phone' => '1', 'website' => 'http://spam']);

        Mail::assertNothingSent();
    }

    public function test_an_invalid_form_sends_nothing(): void
    {
        $this->post('/en/demo', ['name' => '', 'phone' => ''])->assertSessionHasErrors();

        Mail::assertNothingSent();
        $this->assertSame(0, DemoRequest::count());
    }

    public function test_a_mail_failure_never_loses_the_request_or_breaks_the_page(): void
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP is down'));

        $this->post('/fa/demo', ['name' => 'Ahmad', 'phone' => '0700000000'])->assertRedirect()->assertSessionHas('sent');

        $this->assertSame(1, DemoRequest::count());
    }

    public function test_without_recipients_nothing_is_sent(): void
    {
        config(['site.leads_to' => []]);

        $this->post('/en/demo', ['name' => 'Ahmad', 'phone' => '0700000000'])->assertRedirect();

        Mail::assertNothingSent();
        $this->assertSame(1, DemoRequest::count());
    }

    public function test_text_that_is_not_valid_utf8_is_rejected_not_a_server_error(): void
    {
        $this->call("POST", "/en/demo", ["name" => "Ahmad \xAB", "phone" => "0700000000"])->assertStatus(400);

        Mail::assertNothingSent();
        $this->assertSame(0, DemoRequest::count());
    }

}
