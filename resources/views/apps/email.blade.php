@extends('layouts.vertical', ['title' => 'Inbox', 'subTitle' => 'Email'])

@section('css')
@vite(['node_modules/quill/dist/quill.bubble.css', 'node_modules/quill/dist/quill.snow.css'])
@endsection

@section('content')

<div class="row g-1 mb-3">
    <div class="col-xxl-2">
        <div class="offcanvas-xxl offcanvas-start h-100" tabindex="-1" id="EmailSidebaroffcanvas" aria-labelledby="EmailSidebaroffcanvasLabel">
            <div class="card h-100 mb-0" data-simplebar="">
                <div class="card-body">
                    <div class="d-grid">
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#compose-modal">Compose</button>
                    </div>

                    <div class="nav flex-column mt-3" id="email-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link px-0 py-1 active show" id="email-inbox-tab" data-bs-toggle="pill" href="#email-inbox" role="tab" aria-controls="email-inbox" aria-selected="true">
                            <span class="text-danger fw-bold">
                                <i class="bx bxs-inbox fs-16 me-2 align-middle"></i> Inbox
                                <span class="badge badge-soft-danger float-end ms-2">7</span>
                            </span>
                        </a>

                        <a class="nav-link px-0 py-1" id="email-starred-tab" data-bs-toggle="pill" href="#email-starred" role="tab" aria-controls="email-starred" aria-selected="false">
                            <i class="bx bx-star fs-16 align-middle me-2"></i>Starred
                        </a>

                        <a class="nav-link px-0 py-1" id="email-draft-tab" data-bs-toggle="pill" href="#email-draft" role="tab" aria-controls="email-draft" aria-selected="false">
                            <i class="bx bxs-file-doc fs-16 align-middle me-2"></i>Draft<span class="badge badge-soft-info float-end ms-2">32</span>
                        </a>

                        <a class="nav-link px-0 py-1" id="email-sent-tab" data-bs-toggle="pill" href="#email-sent" role="tab" aria-controls="email-sent" aria-selected="false">
                            <i class="bx bx-send fs-16 align-middle me-2"></i>Sent Mail
                        </a>

                        <a class="nav-link px-0 py-1" id="email-trash-tab" data-bs-toggle="pill" href="#email-trash" role="tab" aria-controls="email-trash" aria-selected="false">
                            <i class="bx bx-trash fs-16 align-middle me-2"></i>Trash Mail
                        </a>

                        <a class="nav-link px-0 py-1" id="email-important-tab" data-bs-toggle="pill" href="#email-important" role="tab" aria-controls="email-important" aria-selected="false">
                            <i class="bx bx-tag fs-16 align-middle me-2"></i>Important
                        </a>

                        <h6 class="text-uppercase mt-4">Labels</h6>

                        <a class="nav-link px-0 py-1" id="email-updates-tab" data-bs-toggle="pill" href="#email-updates" role="tab" aria-controls="email-updates" aria-selected="false">
                            <i class="bx bxs-circle font-13 text-info me-2"></i>Updates
                        </a>

                        <a class="nav-link px-0 py-1" id="email-social-tab" data-bs-toggle="pill" href="#email-social" role="tab" aria-controls="email-social" aria-selected="false">
                            <i class="bx bxs-circle font-13 text-primary me-2"></i>Social
                        </a>

                        <a class="nav-link px-0 py-1" id="email-promotion-tab" data-bs-toggle="pill" href="#email-promotion" role="tab" aria-controls="email-promotion" aria-selected="false">
                            <i class="bx bxs-circle font-13 text-secondary me-2"></i>Promotions
                        </a>

                        <a class="nav-link px-0 py-1" id="email-forums-tab" data-bs-toggle="pill" href="#email-forums" role="tab" aria-controls="email-forums" aria-selected="false">
                            <i class="bx bxs-circle font-13 text-success me-2"></i>Forums</a>
                        </a>
                    </div>


                    <div class="mt-5">
                        <h4><span class="badge rounded-pill p-1 px-2 badge-soft-secondary">FREE</span></h4>
                        <h6 class="text-uppercase mt-3">Storage</h6>
                        <div class="progress my-2 progress-sm">
                            <div class="progress-bar progress-lg bg-success" role="progressbar" style="width: 46%" aria-valuenow="46" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="text-muted font-13 mb-0">7.02 GB (46%) of 15 GB used</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-10">
        <div class="card position-relative overflow-hidden h-100">
            <div class="p-3">
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-light d-xxl-none d-flex align-items-center px-2 me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#EmailSidebaroffcanvas" aria-controls="EmailSidebaroffcanvas">
                        <i class="bx bx-menu fs-18"></i>
                    </button>

                    <!-- archive, spam & delete -->
                    <div class="btn-group">
                        <button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Archive">
                            <i class="bx bx-archive fs-18"></i>
                        </button>
                        <button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Mark as spam">
                            <i class="bx bx-info-square fs-18"></i>
                        </button>
                        <button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                            <i class="bx bx-trash fs-18"></i>
                        </button>
                    </div>

                    <!-- move to -->
                    <div class="btn-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Folder">
                        <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bx bx-folder fs-18"></i>
                        </button>
                        <div class="dropdown-menu">
                            <span class="dropdown-header">Move to</span>
                            <a class="dropdown-item" href="javascript: void(0);">Social</a>
                            <a class="dropdown-item" href="javascript: void(0);">Promotions</a>
                            <a class="dropdown-item" href="javascript: void(0);">Updates</a>
                            <a class="dropdown-item" href="javascript: void(0);">Forums</a>
                        </div>
                    </div>

                    <!-- labels -->
                    <div class="btn-group" data-bs-toggle="tooltip" data-bs-placement="top" title="Labels">
                        <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bx bx-bookmarks fs-18"></i>
                        </button>
                        <div class="dropdown-menu">
                            <span class="dropdown-header">Label as :</span>
                            <a class="dropdown-item" href="javascript: void(0);">Updates</a>
                            <a class="dropdown-item" href="javascript: void(0);">Social</a>
                            <a class="dropdown-item" href="javascript: void(0);">Promotions</a>
                            <a class="dropdown-item" href="javascript: void(0);">Forums</a>
                        </div>
                    </div>

                    <!-- more option -->
                    <div class="btn-group" data-bs-toggle="tooltip" data-bs-placement="top" title="More Actions">
                        <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">More</button>
                        <div class="dropdown-menu">
                            <span class="dropdown-header">More Option :</span>
                            <a class="dropdown-item" href="javascript: void(0);">Mark as Unread</a>
                            <a class="dropdown-item" href="javascript: void(0);">Add to Tasks</a>
                            <a class="dropdown-item" href="javascript: void(0);">Add Star</a>
                            <a class="dropdown-item" href="javascript: void(0);">Mute</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content pt-0" id="email-tabContent">
                <div class="tab-pane fade active show" id="email-inbox" role="tabpanel" aria-labelledby="email-inbox-tab">
                    <div>
                        <ul class="nav nav-tabs nav-justified">
                            <li class="nav-item">
                                <a href="#primaryMail" data-bs-toggle="tab" aria-expanded="true" class="nav-link d-flex align-items-center active">
                                    <span class="me-2"><i class="bx bxs-inbox fs-18"></i></span>
                                    <span class="d-none d-md-block">Primary</span>
                                </a>
                            </li> <!-- end nav item -->
                            <li class="nav-item">
                                <a href="#socialMail" data-bs-toggle="tab" aria-expanded="false" class="nav-link d-flex align-items-center">
                                    <span class="me-2"><i class="bx bxs-group fs-18"></i></span>
                                    <span class="d-none d-md-block">Social</span>
                                </a>
                            </li> <!-- end nav item -->
                            <li class="nav-item">
                                <a href="#PromotionsMail" data-bs-toggle="tab" aria-expanded="false" class="nav-link d-flex align-items-center">
                                    <span class="me-2"><i class="bx bxs-purchase-tag fs-18"></i></span>
                                    <span class="d-none d-md-block">Promotions</span>
                                </a>
                            </li> <!-- end nav item -->
                            <li class="nav-item">
                                <a href="#updatesMail" data-bs-toggle="tab" aria-expanded="false" class="nav-link d-flex align-items-center">
                                    <span class="me-2"><i class="bx bxs-info-circle fs-18"></i></span>
                                    <span class="d-none d-md-block">Updates</span>
                                </a>
                            </li> <!-- end nav item -->
                            <li class="nav-item">
                                <a href="#forumsMail" data-bs-toggle="tab" aria-expanded="false" class="nav-link d-flex align-items-center">
                                    <span class="me-2"><i class="bx bxs-chat fs-18"></i></span>
                                    <span class="d-none d-md-block">Forums</span>
                                </a>
                            </li> <!-- end nav item -->
                        </ul> <!-- end nav tabs -->

                        <div class="tab-content text-muted pt-0">
                            <div class="tab-pane show active" id="primaryMail">
                                <ul class="message-list mb-0">
                                    <li class="unread">
                                        <div class="col-mail col-mail-1">
                                            <div class="checkbox-wrapper-mail">
                                                <input type="checkbox" id="InboxChk1">
                                                <label for="InboxChk1" class="toggle"></label>
                                            </div>
                                            <span class="star-toggle bx bx-star"></span>
                                            <span class="bx bxs-tag-alt important-toggle text-warning"></span>
                                            <a data-bs-toggle="offcanvas" href="#email-read" class="title">Daniel J. Olsen</a>
                                        </div>
                                        <div class="col-mail col-mail-2">
                                            <a data-bs-toggle="offcanvas" href="#email-read" class="subject">
                                                Lucas Kriebel (@Daniel J. Olsen) has sent you a direct message on Twitter! &nbsp;&ndash;&nbsp;
                                                <span>
                                                    @Daniel J. Olsen - Very cool :) Nicklas, You have a new direct message.
                                                </span>
                                            </a>
                                            <div class="date">11:49 am</div>
                                        </div>
                                    </li>
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                </ul> <!-- end message list -->
                            </div> <!-- end pimary mail tab pane -->
                            <div class="tab-pane" id="socialMail">
                                <ul class="message-list mb-0">
                                    

                                </ul> <!-- end message list -->
                            </div> <!-- end social mail tab pane -->
                            <div class="tab-pane" id="PromotionsMail">
                                <ul class="message-list mb-0">
                                    
                                    
                                    
                                    
                                </ul> <!-- end message list -->
                            </div> <!-- end promotion mail tab pane -->
                            <div class="tab-pane" id="updatesMail">
                                <ul class="message-list mb-0">
                                    
                                   
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                </ul> <!-- end message list -->
                            </div> <!-- end update mail tab pane -->
                            <div class="tab-pane" id="forumsMail">
                                <ul class="message-list mb-0">
                                    
                                    
                                    
                                    
                                   
                                </ul> <!-- end message list -->
                            </div> <!-- end forum mail tab pane -->
                        </div> <!-- end tab content -->
                    </div>
                </div>

                <div class="tab-pane fade" id="email-starred" role="tabpanel" aria-labelledby="email-starred-tab">
                    <ul class="message-list mb-0">
                        
                        
                        
                    </ul> <!-- end message list -->
                </div>

                <div class="tab-pane fade" id="email-draft" role="tabpanel" aria-labelledby="email-draft-tab">
                    <hr />
                    <div class="text-center mt-2">
                        <p class="mb-0">You don't have any saved drafts.</p>
                        <p class="mb-0">Saving a draft allows you to keep a message you aren't ready to send yet.</p>
                    </div>
                    <hr class="mb-0" />
                </div>

                <div class="tab-pane fade" id="email-sent" role="tabpanel" aria-labelledby="email-sent-tab">
                    <ul class="message-list mb-0">
                        
                    </ul> <!-- end message list -->
                </div>

                <div class="tab-pane fade" id="email-trash" role="tabpanel" aria-labelledby="email-trash-tab">
                    <hr />
                    <div class="text-center mt-2">
                        <p class="mb-0">
                            Messages that have been in Trash more than 30 days will be automatically deleted.
                            <a href="javascript: void(0);" class="text-primary ms-2">Empty Trash Now</a>
                        </p>
                    </div>
                    <hr class="mb-3" />

                    <ul class="message-list mb-0">
                        
                    </ul> <!-- end message list -->
                </div>

                <div class="tab-pane fade" id="email-important" role="tabpanel" aria-labelledby="email-important-tab">
                    <ul class="message-list mb-0">
                       
                    </ul> <!-- end message list -->
                </div>

                <div class="tab-pane fade" id="email-updates" role="tabpanel" aria-labelledby="email-updates-tab">
                    <ul class="message-list mb-0">
                        
                    </ul> <!-- end message list -->
                </div>

                <div class="tab-pane fade" id="email-social" role="tabpanel" aria-labelledby="email-social-tab">
                    <ul class="message-list mb-0">
                        
                    </ul> <!-- end message list -->
                </div>

                <div class="tab-pane fade" id="email-promotion" role="tabpanel" aria-labelledby="email-promotion-tab">
                    <ul class="message-list mb-0">
                        
                    </ul> <!-- end message list -->
                </div>

                <div class="tab-pane fade" id="email-forums" role="tabpanel" aria-labelledby="email-forums-tab">
                    <ul class="message-list mb-0">
                        
                    </ul> <!-- end message list -->
                </div>
            </div> <!-- end tab-content-->

            <div class="px-3 py-2 mt-auto">
                <div class="d-flex align-items-center justify-content-between">
                    <div class=""> Showing 1 - 20 of 289 </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-light btn-sm">
                            <i class="bx bx-chevron-left"></i>
                        </button>
                        <button type="button" class="btn btn-primary btn-sm">
                            <i class="bx bx-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="offcanvas offcanvas-end mail-read position-absolute shadow" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="email-read" aria-labelledby="email-readLabel">
                <div class="offcanvas-header">
                    <div class="d-flex gap-2 align-items-center w-50">
                        <a href="#" role="button" data-bs-dismiss="offcanvas" aria-label="Close">
                            <i class="bx bx-arrow-back fs-18 align-middle"></i>
                        </a>
                        <h5 class="offcanvas-title text-truncate w-50" id="email-readLabel">Medium</h5>
                    </div>

                    <div class="ms-auto">
                        <!-- archive, spam & delete -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Archive">
                                <i class="bx bx-archive fs-18"></i>
                            </button>
                            <button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Mark as spam">
                                <i class="bx bx-info-square fs-18"></i>
                            </button>
                            <button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Delete">
                                <i class="bx bx-trash fs-18"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="offcanvas-body p-0 h-100" data-simplebar>
                    <div class="px-3">
                        <div class="mt-2">
                            <h5>Hi Jorge, How are you?</h5>

                            <hr />

                            <div class="d-flex mb-4 mt-1">
                                <img class="me-2 rounded-circle avatar-sm" src="/images/users/avatar-2.jpg" alt="Generic placeholder image">
                                <div class="flex-grow-1">
                                    <span class="float-end">07:23 AM</span>
                                    <h6 class="m-0">Jonathan Smith</h6>
                                    <small class="text-muted">From: jonathan@domain.com</small>
                                </div>
                            </div>

                            <p><b>Hi Jorge1...</b></p>
                            <div class="text-muted">
                                <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean
                                    commodo ligula eget dolor. Aenean massa. Cum sociis natoque
                                    penatibus et magnis dis parturient montes, nascetur ridiculus
                                    mus. Donec quam felis, ultricies nec, pellentesque eu, pretium
                                    quis, sem.</p>
                                <p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel,
                                    aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut,
                                    imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede
                                    mollis pretium. Integer tincidunt. Cras dapibus. Vivamus
                                    elementum semper nisi.</p>
                                <p>Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor
                                    eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante,
                                    dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra
                                    nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet.
                                    Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies
                                    nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget
                                    condimentum rhoncus, sem quam semper libero, sit amet adipiscing
                                    sem neque sed ipsum. Nam quam nunc, blandit vel, luctus
                                    pulvinar,</p>
                            </div>

                            <hr />

                            <h6> <i class="fa fa-paperclip mb-2"></i> Attachments <span>(3)</span>
                            </h6>

                            <div>
                                <a href="javascript:void(0);">
                                    <img src="/images/small/img-1.jpg" alt="attachment" style="height: 84px;" class="img-thumbnail me-1">
                                </a>
                                <a href="javascript:void(0);">
                                    <img src="/images/small/img-2.jpg" alt="attachment" style="height: 84px;" class="img-thumbnail me-1">
                                </a>
                                <a href="javascript:void(0);">
                                    <img src="/images/small/img-3.jpg" alt="attachment" style="height: 84px;" class="img-thumbnail me-1">
                                </a>
                            </div>

                        </div> <!-- card-box -->
                    </div>
                </div>
                <div class="p-3">
                    <div class="d-flex">
                        <img class="me-2 rounded-circle avatar-sm" src="/images/users/avatar-7.jpg" alt="Generic placeholder image">
                        <div class="flex-grow-1">
                            <div class="mb-2">
                                <div id="snow-editor" style="height: 200px;">
                                    <h3>This is an Air-mode editable area.</h3>
                                    <p><br></p>
                                    <ul>
                                        <li>Select a text to reveal the toolbar.</li>
                                        <li>Edit rich document on-the-fly, so elastic!</li>
                                    </ul>
                                    <p><br></p>
                                    <p>End of air-mode area</p>
                                </div> <!-- end Snow-editor-->
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="button" class="btn btn-primary width-sm" data-bs-dismiss="offcanvas" aria-label="Close">Send</button>
                    </div>
                </div>
            </div>

        </div> <!-- end card -->
    </div> <!-- end col -->
</div> <!-- end row -->

<div class="modal fade compose-mail" id="compose-modal" tabindex="-1" aria-labelledby="compose-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header overflow-hidden bg-primary p-2">
                <h5 class="modal-title text-white" id="compose-modalLabel">New Message</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="overflow-hidden">

                    <div class="mb-2">
                        <input type="email" class="form-control" id="toEmail" placeholder="To: ">
                    </div>
                    <div class="mb-2">
                        <input type="text" class="form-control" id="subject" placeholder="Subject ">
                    </div>

                    <div class="my-2">
                        <div id="snow-editor2" style="height: 200px;"></div>
                    </div>

                    <div class="d-flex float-end">
                        <div class="dropdown me-1">
                            <a href="javascript:void(0);" class="dropdown-toggle arrow-none btn btn-light" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded fs-18"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-up">
                                <a href="javascript:void(0);" class="dropdown-item">Default to full screen</a>
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0);" class="dropdown-item">Label</a>
                                <a href="javascript:void(0);" class="dropdown-item">Palin text mode</a>
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0);" class="dropdown-item">Smart Compose Feedback</a>
                            </div>
                        </div>
                        <a href="javascript: void(0);" class="btn btn-light compose-close"><i class="bx bxs-trash fs-18"></i></a>
                    </div>
                    <div>
                        <a href="javascript: void(0);" class="btn btn-primary">Send</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
@vite(['resources/js/pages/app-email.js'])
@endsection