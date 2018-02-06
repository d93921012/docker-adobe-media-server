# docker-adobe-media-server
在 Docker 中執行 Adobe Media Server

參考 oprearocks/docker-adobe-media-server

原本，Adobe Media Server 必須手動互動式地安裝，很難放到 dockerfile 中，自動建立 image。有人找出方法，將回應先放到 installAMS.input 中，餵給安裝程式。不過，在閱讀授權檔時，會有問題，所以先把 License.txt 刪掉。

以上，是好久以前，在網路搜尋時，找到的討論，現在找不到，只有一些印象。

### 簡要說明
* 安裝檔，預先下載放在自己的伺服器，方便重複測試。
* 將安裝及清除指令儘量湊在一行，減少 layer，減少 image 的大小。
* 加上 --cap_add SYS_PTRACE 的選項，是為了透過 PHP 查詢開啟的檔案。透過 media server 的 API 只能查詢到 RTMP 開啟的檔，無法查詢經 HDS 開啟的檔。
* 透過 PHP-FPM 執行 PHP 網頁，主要目的是查詢開啟的影音檔。
* Users.xml，是用來允許 透過 http 來傳送 admin commands。這個檔，是在全程安裝一次後，再將安裝好的檔案 copy 出來，因為不清楚它的密碼是如何產生的。

## 影音檔目錄的說明
The server was configured with two directories because many customers don't want their media available for download over HTTP. The vod/media folder is for RTMP streaming only. Other customers need their media available over HTTP as well as RTMP. The webroot/vod folder is for these files.

## 檔案說明
* docker 的相關檔案，放在 docker-files 的目錄下
* web 目錄下的 cinfo，是一個簡單的監看程式，我自己平時觀看連線的 IP 和開啟的檔案。