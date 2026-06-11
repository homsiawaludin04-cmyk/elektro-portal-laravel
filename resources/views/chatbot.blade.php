<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chatbot Teknik Elektro Untirta</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/bootstrap.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="stylesheet" href="/css/chatbot.css" />
  </head>
  <body>
    <div class="container-fluid fullscreen-wrapper p-0 m-0">
        <div class="card card-chatbot">

            <div class="card-header header-untirta" style="background: #0056b3 !important; border-bottom: 4px solid #ffcc00 !important; padding: 10px 15px !important; width: 100%; box-sizing: border-box; display: block; clear: both;">
                <table style="width: 100%; border-collapse: collapse; border: none; margin: 0; padding: 0;">
                    <tr>
                        <td style="width: 50px; vertical-align: middle; padding: 0; margin: 0; line-height: 0;">
                            <img src="/images/untirta.png" alt="Logo Untirta" style="height: 42px; width: 42px; display: inline-block; margin: 0; padding: 0; border: none;" />
                        </td>

                        <td style="vertical-align: middle; padding-left: 12px; margin: 0; text-align: left;">
                            <div style="display: block; margin: 0; padding: 0; line-height: 1.2;">
                                <h5 style="color: #ffffff !important; font-family: sans-serif; font-size: 1.15rem !important; font-weight: 700 !important; letter-spacing: 0.5px; margin: 0 !important; padding: 0 !important; line-height: 1.2 !important; white-space: nowrap;">
                                    CHATBOT AKADEMIK TEKNIK ELEKTRO
                                </h5>
                                <small style="color: #ffcc00 !important; font-family: sans-serif; font-size: 0.8rem !important; font-weight: 600 !important; letter-spacing: 0.5px; margin: 0 !important; padding: 0 !important; line-height: 1.2 !important; white-space: nowrap; display: block; margin-top: 2px;">
                                    UNIVERSITAS SULTAN AGENG TIRTAYASA
                                </small>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="chat-box" id="chatWindow">
                <div class="msg-wrapper bot">
                    <div class="chat-bubble">
                        <strong>Bot:</strong> Halo! Ada yang bisa saya bantu seputar kurikulum, laboratorium, beasiswa, atau pendaftaran Tugas Akhir (Skripsi) Teknik Elektro?
                    </div>
                </div>
            </div>

            <div class="card-footer-custom">
                <div class="input-group">
                    <input type="text" id="userInput" class="form-control input-custom" placeholder="Ketik pertanyaan Anda di sini..." autocomplete="off"/>
                    <button class="btn btn-primary btn-custom" id="sendBtn">Kirim</button>
                </div>
            </div>

        </div>
    </div>

    <script>
      $(document).ready(function () {
        $.ajaxSetup({
          headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
          },
        });

        function sendMessage() {
        let message = $("#userInput").val().trim();
        if (message === "") return;

        // 1. Tampilkan Chat Bubble User (Kanan + Avatar)
        $("#chatWindow").append(
            '<div class="msg-wrapper user">' +
            '  <div class="chat-bubble-container">' +
            '    <div class="chat-bubble">' + message + '</div>' +
            '  </div>' +
            '  <img src="/images/user.png" class="chat-avatar" alt="User Avatar" />' + // Ganti dengan path foto user Anda
            '</div>'
        );
        $("#userInput").val("");
        $("#chatWindow").scrollTop($("#chatWindow")[0].scrollHeight);

        // Tampilkan Efek Animasi 3 Titik Melompat Sementara
        let typingId = "typing-" + Date.now();
        $("#chatWindow").append(
            '<div class="msg-wrapper bot" id="' + typingId + '">' +
            '  <img src="/images/admin.png" class="chat-avatar" alt="Bot Avatar" />' +
            '  <div class="chat-bubble-container">' +
            '    <div class="chat-bubble">' +
            '      <div class="typing-indicator"><span></span><span></span><span></span></div>' +
            '    </div>' +
            '  </div>' +
            '</div>'
        );
        $("#chatWindow").scrollTop($("#chatWindow")[0].scrollHeight);

        // 2. Kirim data ke backend Laravel
        $.ajax({
            url: "/chatbot/send",
            type: "POST",
            data: { message: message },
            dataType: "json",
            success: function (response) {
            $("#" + typingId).remove(); // Hapus efek animasi ngetik

            let formattedReply = marked.parse(response.reply);

            // 3. Tampilkan Chat Bubble Bot dengan Render Markdown (Kiri + Avatar)
            $("#chatWindow").append(
                '<div class="msg-wrapper bot">' +
                '  <img src="/images/admin.png" class="chat-avatar" alt="Bot Avatar" />' +
                '  <div class="chat-bubble-container">' +
                '    <div class="chat-bubble">' + formattedReply + '</div>' +
                '  </div>' +
                '</div>'
            );
            $("#chatWindow").scrollTop($("#chatWindow")[0].scrollHeight);
            },
            error: function () {
            $("#" + typingId).remove();
            $("#chatWindow").append(
                '<div class="msg-wrapper bot">' +
                '  <img src="/images/admin.png" class="chat-avatar" alt="Bot Avatar" />' +
                '  <div class="chat-bubble-container">' +
                '    <div class="chat-bubble text-danger"><strong>Bot:</strong> Gagal terhubung ke server.</div>' +
                '  </div>' +
                '</div>'
            );
            },
        });
        }

        $("#sendBtn").click(sendMessage);
        $("#userInput").keypress(function (e) {
          if (e.which == 13) sendMessage();
        });
      });
    </script>
  </body>
</html>
