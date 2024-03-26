<?php
if (!defined('_GNUBOARD_'))
    exit; // 개별 페이지 접근 불가

$validPrefixes = ['noti', 'character', 'system', 'world'];
$prefix = strstr($co_id, '_', true);

$contents = array();

if (in_array($prefix, $validPrefixes)) {
    $sql = "SELECT * FROM {$g5['content_table']} WHERE co_id LIKE '{$prefix}_%'";
    $result = sql_query($sql);
    for ($i = 0; $row = sql_fetch_array($result); $i++) {
        $contents[$i] = $row;
    }
}

add_stylesheet('<link rel="stylesheet" href="' . $content_skin_url . '/layout.css">', 0);
?>

<div id="m-common-container" style="height: 100%;">
    <aside>
        <ul>
            <li><a href="/bbs/content.php?co_id=noti_1">NOTICE</a></li>
            <li><a href="/bbs/content.php?co_id=world_1"">WORLD</a></li>
            <li><a href=" /bbs/content.php?co_id=system_1">SYSTEM</a></li>
            <li><a href="/bbs/content.php?co_id=character_1">CHARACTER</a></li>
        </ul>

    </aside>
    <div class="m-content-container">
        <nav class="content-category">
            <? echo '<ul>';
            foreach ($contents as $content) {
                echo '<li>';
                // echo '<a href="' . G5_BBS_URL . '/content.php?co_id=' . $content['co_id'] . '">' . $content['co_subject'] . '</a>';
                echo '<a class="m-link " href="#" data-id="' . $content['co_id'] . '">' . $content['co_subject'] . '</a>';
                echo '</li>';
            }
            echo '</ul>';
            ?>
        </nav>
        <div class="page">
            <h1 class="m-content-title">
                <?php echo $co['co_subject'] ?>
            </h1>
            <article class="m-article">
                <?php echo $str; ?>
            </article>
        </div>

    </div>

    <!-- 모달 -->
    <div class="modal micromodal-slide" id="world-modal" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div onclick="event.stopPropagation()" class="modal__container" role="dialog" aria-modal="true">
                <main class="modal__content" id="modal-1-content">
                </main>
            </div>
        </div>
    </div>

    <div class="bottom"></div>
</div>


<script src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script>

<script>
    // prefix가 일치하는 모든 게시글
    const contents = <?php echo json_encode($contents); ?>;
    // 현재 게시글 아이디
    let currentId = <?php echo json_encode($co_id); ?>;

    const titleEl = document.querySelector('.m-content-title');
    const articleEl = document.querySelector('.m-article')

    const links = document.querySelectorAll('.m-link');

    links.forEach((el) => el.addEventListener('click', (evt) => {
        currentId = evt.currentTarget.dataset.id;
        if (currentId === 'world_1') {
            location.href = '/bbs/content.php?co_id=world_1'
            return;
        }
        setContent(currentId)
    }))

    const getContent = (id) => {
        const content = contents.find((co) => co.co_id === currentId);
        return ({ subject: content.co_subject, content: content.co_content })

    }

    const setContent = (id) => {
        const { subject, content } = getContent(id);
        titleEl.innerHTML = subject;
        articleEl.innerHTML = content;

        scrollToTop(document.querySelector('.page'))
        initWorldMap();
    }

    // 스크롤
    const scrollToTop = (el) => {
        const target = el || window;

        target.scrollTo({
            top: 0,
            behavior: "smooth",
        });
    }

    // 월드맵 
    const modalContent = document.querySelector('.modal__content');

    let worldContents;

    const openWorldModal = async (id) => {
        if (!worldContents) {
            const res = await fetch('/skin/content/document/fetch-cont.php');
            const data = await res.json();
            worldContents = data;
        }

        const content = worldContents.find((v) => v.co_id === `cont_${id}`);
        if (!content?.co_content) {
            alert('작성된 글이 없습니다.')
            return;
        }
        modalContent.innerHTML = content.co_content;
        MicroModal.show("world-modal");
        scrollToTop(document.querySelector('.modal__container'))

    }

    const initWorldMap = () => {
        const worldMapWrapper = document.querySelector(".world-map-wrapper");
        worldMapWrapper.style.position = 'relative';


        const addLinkArea = (x, y, width, height, id, color) => {
            const clickableArea = document.createElement("div");
            clickableArea.style.position = "absolute";
            clickableArea.style.top = y + "px";
            clickableArea.style.left = x + "px";
            clickableArea.style.width = width + "px";
            clickableArea.style.height = height + "px";
            clickableArea.style.backgroundColor = color;
            clickableArea.style.cursor = "pointer";

            clickableArea.addEventListener("click", (e) => {
                e.stopPropagation();
                openWorldModal(id);
            });

            worldMapWrapper.appendChild(clickableArea);
        };

        if (worldMapWrapper) {
            addLinkArea(480, 180, 200, 350, "1");
            addLinkArea(80, 180, 180, 400, "2");
            addLinkArea(240, 180, 250, 230, "3");
            addLinkArea(230, 40, 250, 150, "4");
            addLinkArea(270, 400, 230, 150, "5");
        }
    }


    MicroModal.init({
        disableScroll: true,
        disableFocus: false,
        awaitCloseAnimation: true,
    });

    const init = () => {
        setContent(currentId)
        initWorldMap();
    }

    init();



</script>